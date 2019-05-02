<?php

namespace Modules\Admin\Services\Ticket;

use App\Models\AlternativeValuation\Order as AltOrder;
use App\Models\Appraisal\DocuVaultOrder;
use App\Models\Appraisal\OrderLog;
use App\Services\CreateS3Storage;
use App\Models\Api\Subscriber;
use App\Models\Api\SubscriberPendingPost;
use App\Models\AlternativeValuation\OrderLog as AltOrderLog;
use App\Models\AlternativeValuation\OrderLogType as AltOrderLogType;
use App\Models\Ticket\Comment;
use App\Models\Ticket\MultiMod;
use App\Models\Ticket\Ticket;
use App\Models\Appraisal\Order;
use App\Models\Ticket\Status;
use Carbon\Carbon;
use Modules\Admin\Contracts\Ticket\{TicketContract, ActivityContract, TicketContentContract};
use App\Notifications\Ticket as TicketNotification;
use App\Models\Ticket\File;
use App\Models\Ticket\Rule;
use App\Models\Ticket\RuleCondition;
use Modules\Admin\Repositories\Ticket\OrderRepository;
use Modules\Admin\Repositories\Users\UserRepository;

class TicketModerationService
{
    protected $storage;
    protected $order;
    protected $ticket;
    protected $notification;
    protected $ticketRepo;
    protected $activityRepo;
    protected $contentRepo;
    protected $mercuryService;
    protected $valutracService;
    protected $fncService;
    protected $userRepository;
    protected $orderRepository;

    protected $update = [];
    protected $activity = [];

    protected $processTicket;
    protected $matchedRules = [];
    protected $matchedConditions = [];

    /**
     * TicketModerationService constructor.
     * @param CreateS3Storage $storage
     * @param OrderService $order
     * @param TicketService $ticket
     * @param TicketContract $ticketRepo
     * @param ActivityContract $activityRepo
     * @param TicketContentContract $contentRepo
     */
    public function __construct(
        CreateS3Storage $storage,
        OrderService $order,
        TicketService $ticket,
        TicketContract $ticketRepo,
        ActivityContract $activityRepo,
        TicketContentContract $contentRepo,
        MercuryService $mercuryService,
        ValuTracService $valuTracService,
        FNCService $FNCService,
        UserRepository $userRepository,
        OrderRepository $orderRepository
    )
    {
        $this->storage = $storage;
        $this->order = $order;
        $this->ticket = $ticket;

        $this->ticketRepo = $ticketRepo;
        $this->activityRepo = $activityRepo;
        $this->contentRepo = $contentRepo;
        $this->mercuryService = $mercuryService;
        $this->valutracService = $valuTracService;
        $this->fncService = $FNCService;
        $this->userRepository = $userRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function create($request)
    {
        // Add moderation
        MultiMod::create($this->prepareModerationData($request));
    }

    /**
     * @param $request
     * @param \App\Models\Ticket\MultiMod $moderation
     */
    public function update($request, $moderation)
    {
        // Update moderation
        $moderation->update($this->prepareModerationData($request));
    }

    /**
     * @param \App\Models\Ticket\MultiMod $moderation
     */
    public function delete($moderation)
    {
        // Delete moderation
        $moderation->delete();
    }

    /**
     * @param \Illuminate\Http\Request $request |array $request
     * @param \App\Models\Ticket\Ticket $ticket
     * @param array $extraOptions
     * @return array
     */
    public function applyTicketModeration($request, $ticket, $extraOptions = [])
    {
        if (is_array($request)) {
            $request = collect($request);
        }

        $options = $this->buildOptions($request, $extraOptions);

        $this->handleOpenOption($options);
        $this->handlePriorityOption($options, $ticket);
        $this->handleStatusOption($options, $ticket);
        $this->handleCategoryOption($options, $ticket);
        $this->handleAssignOption($options);
        $this->handleOrderOption($options, $ticket);
        $this->handleStartOption($options);
        $this->handleParticipantOption($options, $ticket);

        $assignedUser = isset($this->update['assignid']) && $this->update['assignid']
            ? $this->update['assignid']
            : $ticket->assignid;

        $user = userInfo($assignedUser);

        if ($this->update) {
            $ticket->update($this->update);
        }

        $userNotified = $this->notifyAssignedUser($options, $ticket, $user);
        $this->handleCommentOption($options, $request, $ticket, $user, $userNotified);

        return [
            'activity' => $this->activity,
            'update' => $this->update,
            'options' => $options,
            'notified' => $userNotified,
            'sendNotification' => $ticket->sendNotification,
        ];
    }

    /**
     * @param \App\Models\Ticket\Ticket $ticket
     * @param \App\Models\Ticket\MultiMod|\App\Models\Ticket\RuleAction $moderation
     * @return array
     */
    public function applyModeration($ticket, $moderation)
    {
        $options = $this->buildModerationOptions($moderation);
        $options = $this->buildTicketOptions($ticket, $options);

        // Apply Moderation
        return $this->applyTicketModeration([], $ticket, $options);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Ticket\MultiMod $moderation
     * @return array
     */
    public function applyMultiModeration($request, $moderation)
    {
        $result = [];
        $options = $this->buildModerationOptions($moderation);

        // Loop each ticket and apply
        foreach ($request->checked as $id) {
            $ticket = Ticket::find($id);

            if ($ticket) {
                $options = $this->buildTicketOptions($ticket, $options);
                $result[] = $this->applyTicketModeration($request, $ticket, $options);
            }
        }

        return $result;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function processMultiModeration($request)
    {
        $ids = explode(',', $request->checked_tickets);
        $result = [];

        // Loop all tickets load and apply moderation
        foreach ($ids as $id) {
            $ticket = Ticket::find($id);

            if ($ticket) {
                // Apply Moderation
                $result[] = $this->applyTicketModeration($request, $ticket);
            }
        }

        return $result;
    }

    /**
     * @param array $data
     * @return int
     */
    public function createTicket($data)
    {
        // Fix team
        if (isset($data['teamId'])) {
            $data['assignId'] = $data['teamId'];
            $data['assignType'] = config('constants.assign_type_team');
        }

        $ticket = Ticket::create($this->prepareTicketData($data));

        if (!empty($data['files'])) {
            $this->addTicketFiles($ticket->id, $data['files']);
        }

        // Enter content
        if ($data['body']) {
            $this->contentRepo->addTicketContent($ticket->id, 'text', $data['body']);
        }

        // Enter content
        if (!empty($data['htmlbody'])) {
            $this->contentRepo->addTicketContent($ticket->id, 'html', $data['htmlbody']);
        }

        // Add category
        if (!empty($data['category'])) {
            $this->ticket->updateTicketCategory($ticket->id, $data['category']);
        }

        $data['ticketId'] = $ticket->id;

        // Add log
        if ($data['addLog']) {
            $this->order->createOrderLogEntry($data);
        }

        // Apply Rules
        $this->processTicketRules($ticket);

        return $ticket->id;
    }

    // ------------------

    /**
     * @param \App\Models\Ticket\Ticket $ticket
     * @param array $options
     * @return array
     */
    protected function buildTicketOptions($ticket, $options)
    {
        // If we have set to reply make sure we set the subject
        if (!empty($options['reply_checkbox'])) {
            $options['reply_subject'] = sprintf('RE: %s', $ticket->subject);
            $options['reply_to'] = $ticket->from_content;
        }

        // If we have set to reply all make sure we include additional
        if (!empty($options['reply_all'])) {
            $options['reply_additional'] = $ticket->getAdditionalEmails($ticket->cc_content);
        }

        return $options;
    }

    /**
     * @param \App\Models\Ticket\MultiMod|\App\Models\Ticket\RuleAction $moderation
     * @return array
     */
    protected function buildModerationOptions($moderation)
    {
        // Load the info and build options to apply moderation
        $options = [];

        // Open Or Close
        if ($moderation->close_or_open) {
            if ($moderation->close_or_open == config('constants.ticket_open')) {
                $options['open'] = 1;
            } elseif ($moderation->close_or_open == config('constants.ticket_close')) {
                $options['close'] = 1;
            }
        }

        // Public Comment
        if ($moderation->public_comment) {
            $options['public'] = 1;
        }

        // Reply
        if ($moderation->reply) {
            $options['reply_checkbox'] = 1;
        }

        // Reply All
        if ($moderation->reply_all) {
            $options['reply_all'] = 1;
        }

        $commonValues = [
            'assign_to' => 'assign',
            'set_status' => 'status',
            'set_category' => 'category',
            'set_priority' => 'priority',
            'assign_order' => 'orderid',
            'comment' => 'reply_text',
        ];

        foreach ($commonValues as $r => $m) {
            if ($moderation->{$r}) {
                $options[$m] = $moderation->{$r};
            }
        }

        // Participants
        if ($moderation->add_participants) {
            $options['participants'] = explode(',', $moderation->add_participants);
        }

        return $options;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param array $extraOptions
     * @return array
     */
    protected function buildOptions($request, $extraOptions)
    {
        $options = [];

        $requests = [
            'open',
            'close',
            'priority',
            'status',
            'category',
            'assign',
            'orderid',
            'participants',
            'reply_checkbox',
            'reply_text',
            'reply_all',
            'reply_additional',
            'public',
            'attachments',
        ];

        if ($request->has('open_or_close')) {
            if ($request->open_or_close == config('constants.ticket_open')) {
                $options['open'] = 1;
            } elseif ($request->open_or_close == config('constants.ticket_close')) {
                $options['close'] = 1;
            }
        }

        foreach ($requests as $row) {
            if ($request->has($row)) {
                $options[$row] = $request->get($row);
            }
        }

        if ($request->has('start')) {
            $options['start'] = $request->start;
        }

        return array_merge($options, $extraOptions);
    }

    /**
     * @param array $options
     */
    protected function handleOpenOption($options)
    {
        if (!empty($options['open'])) {
            $this->activity[] = 'Opened Ticket';
            $this->update['closedid'] = 0;
            $this->update['closed_date'] = 0;
        } elseif (!empty($options['close'])) {
            $this->activity[] = 'Closed Ticket';
            $this->update['closedid'] = admin()->id;
            $this->update['closed_date'] = time();
        }
    }

    /**
     * @param array $options
     * @param \App\Models\Ticket\Ticket $ticket
     */
    protected function handlePriorityOption($options, $ticket)
    {
        if (!empty($options['priority'])) {
            if ($options['priority'] == config('constants.option_remove')) {
                $this->activity[] = sprintf('Removed Priority %s', $ticket->getPriorityTitle());
                $this->update['priority'] = 0;
            } else {
                $this->activity[] = sprintf(
                    'Updated Priority From %s To %s',
                    $ticket->getPriorityTitle(),
                    $ticket->getPriorityTitle($options['priority'])
                );

                $this->update['priority'] = $options['priority'];
            }
        }
    }

    /**
     * @param array $options
     * @param \App\Models\Ticket\Ticket $ticket
     */
    protected function handleStatusOption($options, $ticket)
    {
        if (!empty($options['status'])) {
            // Add activity and replace into rel table
            if ($options['status'] == config('constants.option_remove')) {
                $this->activity[] = sprintf('Removed Status %s', $ticket->statusName);

                $this->ticket->updateTicketStatus($ticket->id, null, false);

            } else {
                $status = Status::find($options['status']);
                $status = $status ? $status->name : config('constants.not_available');

                $this->activity[] = sprintf('Updated Status To %s', $status);

                $this->ticket->updateTicketStatus($ticket->id, $options['status'], false);
            }
        }
    }

    /**
     * @param array $options
     * @param \App\Models\Ticket\Ticket $ticket
     */
    protected function handleCategoryOption($options, $ticket)
    {
        if (!empty($options['category'])) {
            if ($options['category'] == config('constants.option_remove')) {
                $message = 'Removed Category %s';
                $this->ticket->updateTicketCategory($ticket->id, null, false);

            } else {
                $message = 'Updated Category To %s';
                $this->ticket->updateTicketCategory($ticket->id, $options['category'], false);
            }

            $ticketCategories = $ticket->categories()->orderBy('tickets_category.name', 'asc')->get();

            $view = view('admin::ticket.manager.partials._ticket_category', [
                'ticket' => $ticket,
                'ticketCategories' => $ticketCategories,
            ])->render();

            $this->activity[] = sprintf($message, $view);
        }
    }

    /**
     * @param array $options
     */
    protected function handleAssignOption($options)
    {
        if (!empty($options['assign'])) {
            $assignType = config('constants.assign_type_team');
            $assignId = 0;

            if ($options['assign'] == config('constants.option_remove')) {
                $this->update['assignid'] = 0;
                $this->update['assigntype'] = $assignType;
            } else {
                if (strpos($options['assign'], 'team_') !== false) {
                    $assignId = str_replace('team_', '', $options['assign']);
                } elseif (strpos($options['assign'], 'user_') !== false) {
                    $assignType = config('constants.assign_type_user');
                    $assignId = str_replace('user_', '', $options['assign']);
                }

                $assignTitle = $this->ticketRepo->getTicketAssignTitle([
                    'assigntype' => $assignType,
                    'assignid' => $assignId
                ]);

                $this->activity[] = sprintf('Assigned To %s', $assignTitle);

                $this->update['assignid'] = $assignId;
                $this->update['assigntype'] = $assignType;
            }
        }
    }

    /**
     * @param array $options
     * @param \App\Models\Ticket\Ticket $ticket
     */
    protected function handleOrderOption($options, $ticket)
    {
        if (!empty($options['orderid'])) {
            $previous = $ticket->type . '-' . $ticket->orderid;
            $orderId = $options['orderid'];
            $orderSplit = explode('-', $orderId);
            $orderChanged = false;

            if (count($orderSplit) == 2) {
                if ($orderSplit[1] != $ticket->orderid || $previous != $orderId) {
                    $this->update['orderid'] = $orderSplit[1];
                    $this->update['type'] = $orderSplit[0];
                    $orderChanged = true;
                }
            } else {
                $order = Order::find($orderId);

                // We assume it's an appraisal
                if ($orderId != $ticket->orderid && $order) {
                    $this->update['orderid'] = $orderId;
                    $this->update['type'] = config('constants.order_type_appraisal');
                    $orderChanged = true;
                }
            }

            if ($orderChanged) {
                $this->activity[] = sprintf('Assinged Order #%s', $this->update['orderid']);
            }

        } elseif ($ticket->orderid && isset($options['orderid']) && $options['orderid'] == '') {
            // Clear order
            $this->update['orderid'] = 0;
            $this->update['type'] = '';
            $this->activity[] = sprintf('Removed Order Assigned #%s', $ticket->orderid);
        }
    }

    /**
     * @param array $options
     */
    protected function handleStartOption($options)
    {
        if (!empty($options['start'])) {
            $this->update['close_start'] = $options['start'];
            $this->update['close_end'] = time();
        }
    }

    /**
     * @param array $options
     * @param \App\Models\Ticket\Ticket $ticket
     */
    protected function handleParticipantOption($options, $ticket)
    {
        if (!empty($options['participants'])) {
            // Add selected participants
            $names = $this->ticket->updateTicketParticipants($ticket->id, $options['participants']);

            $this->activity[] = sprintf('Updated Participants:<br>%s', implode('<br>', $names));
        }
    }

    /**
     * @param array $options
     * @param \App\Models\Ticket\Ticket $ticket
     * @param \App\Models\User $user
     * @return bool
     */
    protected function notifyAssignedUser($options, $ticket, $user)
    {
        // Do we need to notify the user who is assigned to this ticket
        if (!empty($options['assign']) && $ticket->sendNotification && $user) {
            $message = $options['reply_text'] ?? '';

            if ($user->user_type == 1) {
                $user->notify(new TicketNotification($ticket, 'assign', $message));

                return true;
            }
        }

        return false;
    }

    /**
     * @param array $options
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Ticket\Ticket $ticket
     * @param \App\Models\User $user
     * @param bool $userNotified
     */
    protected function handleCommentOption($options, $request, $ticket, $user, $userNotified)
    {
        if (!empty($options['reply_checkbox'])) {
            if (!empty($options['reply_text']) && $request->get('reply_to', $ticket->from_content)) {

                // Do we need to notify the user who is assigned to this ticket
                if (!$userNotified && $ticket->sendNotification) {
                    if ($user && $user->user_type == 1) {
                        $user->notify(new TicketNotification($ticket, 'comment', $options['reply_text']));
                    }
                }

                // Notify Participants
                $this->notifyParticipants($ticket, $options['reply_text'], 'comment');

                // Send Email & Add Comment
                $this->replyTicket($ticket, $request, $options);
            }

        } else {
            // Just add comment
            if (isset($options['reply_text']) && strlen(strip_tags($options['reply_text'])) > 1) {
                // Add Comment
                $this->addComment([
                    'ticket_id' => $ticket->id,
                    'comment' => $options['reply_text'],
                    'is_public' => $options['public'] ?? 0,
                ]);

                // Do we need to notify the user who is assigned to this ticket
                if (!$userNotified && $ticket->sendNotification) {
                    if ($user && $user->user_type == 1) {
                        $user->notify(new TicketNotification($ticket, 'comment', $options['reply_text']));
                    }
                }

                // Notify Participants
                $this->notifyParticipants($ticket, $options['reply_text'], 'comment');
            }
        }
    }

    /**
     * @param \App\Models\Ticket\Ticket $ticket
     * @param \Illuminate\Http\Request $request
     * @param array $options
     */
    protected function replyTicket($ticket, $request, $options)
    {
        // Make sure to email is valid
        $to = trim($request->get('reply_to', $ticket->from_content));
        $subject = $request->get('reply_subject', sprintf('RE: %s', $ticket->subject));
        $content = $options['reply_text'];
        $replyAll = (isset($options['reply_all']) && isset($options['reply_additional']))
            ? explode("\n", $ticket->getAdditionalEmails($options['reply_additional']))
            : null;

        // Build CC
        $cc = [];
        if ($replyAll) {
            foreach ($replyAll as $email) {
                if (filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                    $cc[] = $email;
                }
            }
        }

        // Send email
        \Mail::to($to)
            ->cc($cc)
            ->send(new \App\Mail\ReplyTicket($content, $subject));

        if ($ticket->orderid) {
            $this->addTicketLog($ticket, $subject, $content, $to, $cc);
        }

        // Add Comment
        $this->addComment([
            'to_address' => $to,
            'ticket_id' => $ticket->id,
            'comment' => $subject,
            'html_content' => $content,
            'additional_addresses' => $replyAll,
            'is_public' => 1,
        ]);
    }

    /**
     * @param array $data
     */
    protected function addComment($data)
    {
        $additional = $data['additional_addresses'] ?? null;
        if ($additional && is_array($additional)) {
            $additional = implode(', ', $additional);
        }

        Comment::create([
            'ticket_id' => $data['ticket_id'],
            'comment' => $data['comment'],
            'is_public' => $data['is_public'],
            'to_address' => $data['to_address'] ?? '',
            'html_content' => $data['html_content'] ?? null,
            'additional_addresses' => $additional,
            'attachments' => $data['attachments'] ?? null,
            'created_by' => admin()->id,
            'created_date' => time(),
        ]);
    }

    /**
     * @param \App\Models\Ticket\Ticket $ticket
     * @param string $comment
     * @param string $type
     * @return bool
     */
    protected function notifyParticipants($ticket, $comment, $type = 'comment')
    {
        // See if we have mentions
        preg_match_all('/mention:(\d+)/', $comment, $matches);
        $mentions = $matches[1] ?? null;

        // Load participants
        $participants = $ticket->participates;

        if (!$participants->count() && !$mentions) {
            return false;
        }

        // Build array of user emails
        foreach ($participants as $row) {
            $user = userInfo($row->user_id);
            if (!$user || $user->user_type != 1 || $user->user_id == admin()->id) {
                continue;
            }

            if ($ticket->assignid != $user->user_id) {
                $user->notify(new TicketNotification($ticket, $type, $comment));
            }
        }

        // Build mentions
        if ($mentions) {
            foreach ($mentions as $id) {
                $user = userInfo($id);
                if (!$user || $user->user_type != 1) {
                    continue;
                }

                $email = trim(strtolower($user->email));
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    continue;
                }

                $user->notify(new TicketNotification($ticket, $type, $comment));
            }
        }

        return true;
    }

    /**
     * @param \App\Models\Ticket\Ticket $ticket
     * @param string $subject
     * @param string $content
     * @param string $to
     * @param array $cc
     */
    protected function addTicketLog($ticket, $subject, $content, $to, $cc)
    {
        $type = AltOrderLogType::where('code', 'EMAIL')->first();

        if ($ticket->type == config('constants.order_type_alt')) {
            AltOrderLog::create([
                'order_id' => $ticket->orderid,
                'sub_order_id' => 0,
                'client_visible' => 0,
                'agent_visible' => 0,
                'type_id' => $type->id,
                'message' => $subject,
                'userid' => admin()->id,
                'dts' => date('Y-m-d H:i:s'),
                'html_content' => $content,
            ]);

        } else {
            // See if we need to send a post back if this was created by an api user who has post backs set
            $order = Order::find($ticket->orderid);
            if ($order->api_user) {
                $this->addApiPendingPosts($order);
            }

            $data = [
                'ticketid' => $ticket->id,
                'userid' => admin()->id,
                'type_id' => $type->id,
                'dts' => date('Y-m-d H:i:s'),
                'email' => $to . (count($cc) ? (', ' . implode(', ', $cc)) : ''),
                'html_content' => $content,
                'is_highlight' => 0,
                'is_client_visible' => 0,
                'is_appr_visible' => 0,
            ];

            $this->order->addOrderLog($ticket->orderid, $subject, $data);

            if (admin()->id == $order->orderedby) {
                // If this is a mercury order then send the status update
                if ($order->is_mercury) {
                    $mercury = new MercuryService();
                    $result = $mercury->sendMessage($order, $subject, $content);

                    // Need to create new support ticket
                    if (is_array($result)) {
                        $this->createTicket($result);
                    }
                }

                // If this is a valutrac then send status update
                if ($order->is_valutrac) {
                    $valuTrac = new ValuTracService($this->order);
                    $valuTrac->sendMessage($order, $subject, $content);
                }

                // If this is a fnc then send status update
                if ($order->is_fnc) {
                    $fnc = new FNCService($this->order);
                    $fnc->sendMessage($order, $subject, $content);
                }
            }
        }
    }

    /**
     * @param \App\Models\Appraisal\Order $order
     */
    protected function addApiPendingPosts($order)
    {
        $rows = Subscriber::where('api_subscriber.api_id', $order->api_user)
            ->leftJoin('api_subscriber_type', 'api_subscriber_type.subscriber_id', '=', 'api_subscriber.id')
            ->leftJoin('api_subscriber_pending_post', function ($join) use ($order) {
                $join->on('api_subscriber_pending_post.subscriber_id', '=', 'api_subscriber.id');
                $join->where('api_subscriber_pending_post.rel_id', '=', $order->id);
            })
            ->whereNull('api_subscriber_pending_post.id')
            ->where('api_subscriber.subscribe_active', 1)
            ->where('api_subscriber_type.type', 'appraisal_log')
            ->get();

        foreach ($rows as $row) {
            SubscriberPendingPost::create([
                'subscriber_id' => $row->id,
                'rel_id' => $order->id,
                'created_date' => time()
            ]);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function prepareModerationData($request)
    {
        $data = [
            'title' => $request['title'],
            'description' => $request['description'],
            'is_active' => $request['is_active'],
            'public_comment' => $request['public_comment'],
            'reply' => $request['reply'],
            'reply_all' => $request['reply_all'],
            'close_or_open' => $request['close_or_open'],
            'assign_to' => $request['assign_to'],
            'set_status' => $request['set_status'],
            'set_category' => $request['set_category'],
            'set_priority' => $request['set_priority'],
            'assign_order' => $request['assign_order'],
            'comment' => $request['reply_text'],
            'add_participants' => isset($request['participants'])
                ? implode(',', $request['participants'])
                : '',
        ];

        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function prepareTicketData($data)
    {
        return [
            'orderid' => intval($data['orderId']),
            'closedid' => isset($data['closedid']) ? intval($data['closedid']) : 0,
            'type' => $data['type'] ?? '',
            'userid' => $data['userId'] ?? 0,
            'assignid' => $data['assignId'] ?? 0,
            'assigntype' => $data['assignType'] ?? '',
            'tix_type' => $data['ticketType'] ?? 7,
            'to_content' => $data['to'] ?? '',
            'from_content' => $data['from'] ?? '',
            'cc_content' => $data['cc'] ?? '',
            'created_date' => $data['created'] ?? time(),
            'subject' => $data['subject'] ?? '',
            'closed_date' => $data['closed'] ?? 0,
            'has_files' => (!empty($data['has_files']) || !empty($data['files'])) ? 1 : 0,
        ];
    }

    /**
     * @param int $ticketId
     * @param array $files
     * @param int $userId
     * @return array
     */
    public function addTicketFiles($ticketId, $files, $userId = 0)
    {
        $new = [];

        foreach ($files as $file) {
            $fileName = $file['filename'];
            $fileName = preg_replace("/ /", "_", $fileName);
            $fileName = preg_replace("/[^a-zA-Z0-9_.-]+/", "", $fileName);
            $fileName = \Illuminate\Support\Str::random(5) . '_' . $fileName;

            $content = isset($file['body']) ? base64_decode($file['body']) : $file['text'];

            $path = config('app.ticket_folder') . '/' . $ticketId;

            // Upload file to server
            $s3 = $this->storage->make();
            $s3->put($path.$fileName, $content);

            $file = File::create([
                'tixid' => $ticketId,
                'created_at' => time(),
                'created_by' => $userId ?: admin()->id,
                'filename' => $fileName,
                'is_aws' => 1,
                'file_size' => strlen($content),
            ]);

            $new[$fileName] = $file->id;
        }

        return $new;
    }

    /**
     * @param \App\Models\Ticket\Ticket $ticket
     * @return bool
     */
    protected function processTicketRules($ticket)
    {
        $this->processTicket = $ticket;

        // Load active rules
        $rules = Rule::where('is_active', 1)->orderBy('title', 'asc')->get();
        if (!$rules->count()) {
            return false;
        }

        // Loop all rules
        foreach ($rules as $rule) {
            // Process Single rule
            $this->processRule($rule);
        }

        // If we have matches then apply actions
        foreach ($this->matchedRules as $match) {
            if ($match->action) {
                // Apply ticket rule action
                $this->applyModeration($this->processTicket, $match->action);

                // Apply ticket multi moderation
                if ($match->action->moderation) {
                    $this->applyModeration($this->processTicket, $match->action->moderation);
                }
            }

            // Add activity stating this rule was matched for this ticket
            $this->activityRepo->addTicketActivity(
                $this->processTicket->id, sprintf('Applied Rule: %s', $match->title)
            );
        }
    }

    /**
     * @param \App\Models\Ticket\Rule $rule
     * @return bool
     */
    protected function processRule($rule)
    {
        // Load conditions
        if (!$rule->conditions) {
            return false;
        }

        // How many matches
        $matches = 0;
        $isMatched = false;

        // Loop each rule condition to match against the ticket info
        foreach ($rule->conditions as $condition) {
            $matched = $this->processCondition($condition);
            if ($matched) {
                $matches++;

                if ($rule->match_type == Rule::MATCH_TYPE_ANY) {
                    // If we matched one and rule match type is any then we can
                    // stop and mark this rule as matched
                    $this->matchedRules[$rule->id] = $rule;
                    $isMatched = true;

                    break;
                }
            }
        }

        // If we matched all rules then add it
        if ($matches == count($rule->conditions)) {
            $this->matchedRules[$rule->id] = $rule;
            $isMatched = true;
        }

        return $isMatched;
    }

    /**
     * @param \App\Models\Ticket\RuleCondition $condition
     * @return bool
     */
    protected function processCondition($condition)
    {
        $matched = false;
        $matchedValue = null;

        // Figure out how to match
        switch ($condition->condition_key) {
            case RuleCondition::CONDITION_FROM_ADDRESS:
                $matchedValue = $this->processTicket->from_content;
                $matched = $this->processConditionValue($condition, $this->processTicket->from_content);
                break;

            case RuleCondition::CONDITION_TO_ADDRESS:
                $matchedValue = $this->processTicket->to_content;
                $matched = $this->processConditionValue($condition, $this->processTicket->to_content);
                break;

            case RuleCondition::CONDITION_CC_ADDRESS:
                $matchedValue = $this->processTicket->cc_content;
                $matched = $this->processConditionValue($condition, $this->processTicket->cc_content);
                break;

            case RuleCondition::CONDITION_SUBJECT:
                $matchedValue = $this->processTicket->subject;
                $matched = $this->processConditionValue($condition, $this->processTicket->subject);
                break;

            case RuleCondition::CONDITION_BODY:
                $content = $this->contentRepo->getContent($this->processTicket->id, 'html');

                $matchedValue = substr($content, 0, 200);
                $matched = $this->processConditionValue($condition, $content);
                break;

            case RuleCondition::CONDITION_HAS_ATTACHMENTS:
                $matchedValue = $this->processTicket->files()->count();
                $matched = (bool)($matchedValue > 0);
                break;

            case RuleCondition::CONDITION_NO_ATTACHMENTS:
                $matchedValue = $this->processTicket->files()->count();
                $matched = (bool)($matchedValue == 0);
                break;

            case RuleCondition::CONDITION_CATEGORY:
                $hasCategory = false;
                if ($this->processTicket->categories) {
                    foreach ($this->processTicket->categories as $category) {
                        if ($category->id == $condition->condition_value) {
                            $hasCategory = true;
                            break;
                        }
                    }
                }
                $matched = $hasCategory;
                break;

            default:
                // unknown type
                break;
        }

        if ($matched) {
            $condition->haystack = $matchedValue;
            $this->matchedConditions[$condition->rule_id][] = $condition;
        }

        return $matched;
    }

    /**
     * @param \App\Models\Ticket\RuleCondition $condition
     * @param string $haystack
     * @return bool
     */
    protected function processConditionValue($condition, $haystack)
    {
        switch ($condition->condition_match_type) {
            case RuleCondition::MATCH_TYPE_CONTAINS:
                return stristr($haystack, $condition->condition_value);

            case RuleCondition::MATCH_TYPE_DOES_NOT_CONTAIN:
                return !stristr($haystack, $condition->condition_value);

            case RuleCondition::MATCH_TYPE_BEGINS_WITH:
                $length = strlen($condition->condition_value);
                return (substr($haystack, 0, $length) === $condition->condition_value);

            case RuleCondition::MATCH_TYPE_ENDS_WITH:
                $length = strlen($condition->condition_value);
                if ($length == 0) {
                    return true;
                }

                return (substr($haystack, -$length) === $condition->condition_value);

            case RuleCondition::MATCH_TYPE_EQUAL_TO:
                return strtolower($condition->condition_value) == strtolower($haystack);

            case RuleCondition::MATCH_TYPE_REGEX:
                return preg_match('/' . preg_quote($condition->condition_value) . '/i', $haystack);
        }

        return false;
    }


    /**
     * @param $subject
     * @return array
     */
    public function getOrderIdFromSubject($subject = '')
    {
        preg_match('/[14|20|25|50]\d{7,}/i', $subject, $matches);
        $orderId = isset($matches[0]) && intval($matches[0]) ? $matches[0] : 0;
        if ($orderId) {
            // See if this is an appraisal
            $match = $this->getGlobalOrderTypeById($orderId);
            if ($match) {
                return ['type' => $match, 'id' => $orderId];
            }
        }

        return ['type' => null, 'id' => 0];
    }

    /**
     * @param $id
     * @return \Illuminate\Config\Repository|mixed|null
     */
    public function getGlobalOrderTypeById($id)
    {
        $type = null;

        // Check if we have an appraisal
        if (Order::find($id)) {
            $type = config('constants.order_type_appraisal');
        } elseif (AltOrder::find($id)) {
            $type = config('constants.order_type_alt');
        } elseif (DocuVaultOrder::find($id)) {
            $type = config('constants.order_type_vault');
        }

        return $type;
    }

    /**
     * @param $orderId
     * @param $orderType
     * @return array
     */
    public function getTeamInformation($orderId, $orderType)
    {
        // Routing Rules
        $teamId = null;
        $teamType = null;

        // See if we have an order id and order type
        if ($orderId) {
            // Get the team id by type
            switch ($orderType) {
                case config('constants.order_type_appraisal'):
                    $order = Order::find($orderId);
                    if ($order) {
                        $teamId = $order->team_id;
                        $teamType = 'T';
                    }
                    break;
            }
        }

        return ['teamId' => $teamId, 'teamType' => $teamType];
    }

    /**
     * @param $item
     * @return string
     */
    public function getEmailAddressesComma($items = [])
    {
        $result = [];
        if ($items) {
            foreach ($items as $item) {
                $result[$item['email']] = $item['email'];
            }
        }

        return implode(',', $result);
    }

    /**
     * @param $content
     * @param $id
     * @param array $filesUploaded
     * @return mixed
     */
    public function getSupportTicketContent($content = '', $id, $filesUploaded = [])
    {
        // Match images starting with cid:
        preg_match_all('/src="cid:([^"]+)"/', $content, $matches);
        if (isset($matches[0]) && count($matches[0])) {
            foreach ($matches[0] as $k => $v) {
                $element = $matches[1][$k];
                if (!$element) {
                    continue;
                }

                // Explode element
                $explode = explode('@', $element);
                if (count($explode) >= 2) {
                    $element = $explode[0];
                }

                // Replace with the image link
                if ($filesUploaded && isset($filesUploaded[$element])) {
                    $link = env('APP_URL') . '/admin/tickets.php?action=view-image&fileId=' . $filesUploaded[$element];
                } else {
                    $link = env('APP_URL') . '/admin/tickets.php?action=view-image&id=' . $id . '&image=' . $element;
                }

                $content = str_replace($v, sprintf('src="%s"', $link), $content);
            }
        }
        return $content;
    }

    /**
     * @param $type
     * @return int|mixed
     */
    public function getOrderLogTypeId($type)
    {
        $row = AltOrderLogType::where('code', $type)->first();
        if (!$row) {
            return 1;
        }
        return $row->id;
    }

    /**
     * @param $data
     * @return bool|mixed
     */
    public function addNewLogEntry($data = [])
    {
        $isPublic = (isset($data['public']) && ($data['public'] == 'Y' || $data['public'] == 1)) ? 1 : 0;
        $highlight = (isset($data['highlight']) && ($data['highlight'] == 'Y' || $data['highlight'] == 1)) ? 1 : 0;

        // New Log Entry
        try {
            // See if we need to send a post back if this was created by an api user who has
            // post backs set
            $order = Order::find($data['orderid']);
            if ($order && $order->api_user) {
                $this->apiAddPendingPostBack($order->api_user, $data['orderId'], 'appraisal_log');
            }

            $info = [
                'orderid' => intval($data['orderId']),
                'dts' => $data['date'] ?? date('Y-m-d H:i:s'),
                'userid' => $data['userId'] ? intval($data['userId']) : admin()->id,
                'info' => $data['info'],
                'ticketid' => $data['ticketId'] ?? 0,
                'is_highlight' => $highlight,
                'email' => $data['email'] ?? '',
                'html_content' => $data['html_content'] ?? '',
                'type_id' => $data['typeId'] ?? -1,
                'is_client_visible' => $data['clientVisible'] ?? $isPublic,
                'is_appr_visible' => $data['apprVisible'] ?? $isPublic,
            ];
            $orderLog = new OrderLog();
            $orderLog->orderid = $info['orderid'];
            $orderLog->dts = $info['dts'];
            $orderLog->userid = $info['userid'];
            $orderLog->info = $info['info'];
            $orderLog->ticketid = $info['ticketid'];
            $orderLog->is_highlight = $info['is_highlight'];
            $orderLog->email = $info['email'];
            $orderLog->html_content = $info['html_content'];
            $orderLog->type_id = $info['type_id'];
            $orderLog->is_client_visible = $info['is_client_visible'];
            $orderLog->is_appr_visible = $info['is_appr_visible'];
            $orderLog->save();

            // If this is a mercury order then send the status update
            if ($order->is_mercury && ($info['is_client_visible'] || $info['userid'] == $order->orderedby)) {
                $this->mercuryService->sendMessage($order, MercuryService::COMMENT_ACTION_REQUIRED, sprintf("%s\n%s", $info['info'], strip_tags($info['html_content'])));
            }

            // If this is a valutrac then send status update
            if ($order->is_valutrac && ($info['is_client_visible'] || $info['userid'] == $order->orderedby)) {
                $this->valutracService->sendMessage($order, $info['info'], $info['html_content']);
            }

            // If this is a fnc then send status update
            if ($order->is_fnc && ($info['is_client_visible'] || $info['userid'] == $order->orderedby)) {
                $this->fncService->sendMessage($order, $info['info'], $info['html_content']);
            }
            return $orderLog->id;

        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return false;
        }
    }

    /**
     * @param $id
     * @param $relId
     * @param $type
     */
    protected function apiAddPendingPostBack($id, $relId, $type)
    {
        // Check if we have a subscription url

        $rows = Subscriber::leftJoin('api_subscriber_type t', 't.subscriber_id', '=', 'api_subscriber.id')
            ->leftJoin('api_subscriber_pending_post p', function ($join) use ($relId) {
                return $join->on('api_subscriber.id', '=', 'p.subscriber_id')
                    ->where('p.rel_id', $relId);
            })->where('api_subscriber.api_id', $id)
            ->where('t.type', $type)
            ->whereNull('p.id')
            ->where('api_subscriber.subscribe_active', 1)->get();

        if ($rows) {
            foreach ($rows as $row) {
                try {
                    $pendingPost = new SubscriberPendingPost();
                    $pendingPost->subscriber_id = $row->id;
                    $pendingPost->rel_id = $relId;
                    $pendingPost->created_date = Carbon::now()->timestamp;
                    $pendingPost->save();
                } catch (\Exception $e) {
                    \Log::error($e->getMessage());
                }
            }
        }
    }

    /**
     * @param $data
     * @return array
     */
    public function createTicketFromEmail($data = [])
    {
        $ticket = new Ticket();
        $log = [];

        $subjectParsed = $this->getOrderIdFromSubject($data['messageSubject']);
        $data['orderid'] = $subjectParsed['id'];
        $ticket->orderid = $subjectParsed['id'];
        $data['ordertype'] = $subjectParsed['type'];

        // Parse Team
        $teamParsed = $this->getTeamInformation($data['orderid'], $data['ordertype']);
        $data['teamid'] = $teamParsed['teamId'];
        $data['teamtype'] = $teamParsed['teamType'];

        // Figure the user id based on email from
        $userId = 1;
        $senderEmail = isset($data['sender'][0]['email']) ? $data['sender'][0]['email'] : null;
        $data['from'] = $senderEmail;

        if ($senderEmail) {
            $user = $this->userRepository->getUserInfoByEmailAddress($senderEmail);
            if ($user) {
                $userId = $user->id;
            }
        }

        $newId = null;

        // Clean subject
        $data['subject'] = preg_replace('/[^[:print:]]/', '', $data['messageSubject']);

        // Assign values
        $ticket->orderid = intval($data['orderid']);
        $ticket->userid = $userId;
        $ticket->created_date = $data['messageUnixDate'];
        $ticket->assignid = intval($data['teamid']);
        $ticket->assigntype = (string)$data['teamtype'];
        $ticket->tix_type = 0;
        $ticket->to_content = $this->getEmailAddressesComma($data['messageTo']);
        $ticket->from_content = $this->getEmailAddressesComma($data['messageSender']);
        $ticket->cc_content = $this->getEmailAddressesComma($data['messageCc']);
        $ticket->subject = $data['subject'];
        $ticket->type = (string)$data['ordertype'];
        $ticket->has_files = ($data['attachments']) ? 1 : 0;
        $ticket->is_email_import = 1;
        $ticket->save();


        $filesUploaded = [];

        // Add attachments
        if ($data['attachments'] && count($data['attachments'])) {
            $filesUploaded = $this->addTicketFiles($ticket->id, $data['attachments'], $userId);
            foreach ($filesUploaded as $name => $id) {
                $log[] = '--- New File ID: ' . $id;
            }
        }

        // Convert required elements for this ticket
        $htmlContent = $data['messageHtmlBody'];
        $textContent = $data['messageTextBody'];

        $htmlContent = $this->getSupportTicketContent($htmlContent, $newId, $filesUploaded);

        // Fix HTML
        //$htmlContent = cleanHtmLawedContent($htmlContent);

        if (strip_tags($htmlContent) == '' && $textContent) {
            $htmlContent = $textContent;
        }

        // Enter content
        if ($textContent) {
            try {
                $log[] = "-- Inserting Text Content";
                $this->contentRepo->addTicketContent($ticket->id, 'text', $textContent);
            } catch (\Exception $e) {
                \Log::notice($e->getMessage());
            }
        }

        // Enter content
        if ($htmlContent) {
            try {
                $log[] = "-- Inserting HTML Content";
                $this->contentRepo->addTicketContent($ticket->id, 'html', $htmlContent);
            } catch (\Exception $e) {
                \Log::notice($e->getMessage());
            }
        }

        // Create support ticket
        if ($data['orderid'] && $data['ordertype']) {
            switch ($data['ordertype']) {
                case config('constants.order_type_appraisal'):
                    $log[] = "-- Adding Log Entry \n";
                    $this->addNewLogEntry([
                        'orderId' => $data['orderid'],
                        'userId' => $userId,
                        'info' => $data['subject'],
                        'ticketId' => $newId,
                        'html_content' => $htmlContent,
                        'email' => $data['from']
                    ]);
                    break;

                case config('order_type_alt'):
                    $log[] = "-- Adding Log Entry \n";
                    $altOrderLog = new AltOrderLog();
                    $altOrderLog->order_id = $data['orderid'];
                    $altOrderLog->type_id = $this->getOrderLogTypeId('PROCESS');
                    $altOrderLog->message = $data['subject'];
                    $altOrderLog->ticketid = $ticket->id;
                    $altOrderLog->email = $data['from'];
                    $altOrderLog->userid = $userId;
                    $altOrderLog->dts = Carbon::now()->format('Y-m-d H:i:s');
                    $altOrderLog->html_content = $htmlContent;
                    $altOrderLog->save();
                    break;
            }
        }
        $log[] = '-- Apply Rules';
        $this->processTicketRules($ticket);
        return $log;
    }
}
