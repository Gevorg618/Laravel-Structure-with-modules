<?php

namespace Modules\Admin\Http\Controllers\Ticket;

use Modules\Admin\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\Ticket\GetTicketsRequest;
use Modules\Admin\Http\Requests\Ticket\TicketRequest;
use Yajra\Datatables\Datatables;
use App\Services\CreateS3Storage;
use Modules\Admin\Services\Ticket\{TicketService, TicketModerationService, AssignmentService, TemplateService, OrderService};
use Modules\Admin\Contracts\Ticket\{TicketContentContract, TicketFileContract, ActivityContract, CategoryContract, StatusContract, TicketContract, OrderContract};
use Modules\Admin\Repositories\Users\UserRepository;
use App\Models\Ticket\{Ticket, File, Comment, MultiMod} ;

class ManagerController extends AdminBaseController
{
    protected $userRepo;
    protected $ticketRepo;
    protected $statusRepo;
    protected $categoryRepo;

    protected $storage;
    protected $order;
    protected $ticket;
    protected $template;
    protected $moderation;
    protected $assignment;

    /**
     * ManagerController constructor.
     * @param TicketContract $ticketRepo
     * @param StatusContract $statusRepo
     * @param CategoryContract $categoryRepo
     * @param UserRepository $userRepo
     * @param CreateS3Storage $storage
     * @param TicketModerationService $moderation
     * @param AssignmentService $assignment
     * @param TemplateService $template
     * @param TicketService $ticket
     * @param OrderService $order
     */
    public function __construct(
        // UserContract $userRepo,
        TicketContract $ticketRepo,
        StatusContract $statusRepo,
        CategoryContract $categoryRepo,
        UserRepository $userRepo,
        CreateS3Storage $storage,
        TicketModerationService $moderation,
        AssignmentService $assignment,
        TemplateService $template,
        TicketService $ticket,
        OrderService $order
    )
    {
        $this->userRepo = $userRepo;
        $this->ticketRepo = $ticketRepo;
        $this->statusRepo = $statusRepo;
        $this->categoryRepo = $categoryRepo;

        $this->ticket = $ticket;
        $this->moderation = $moderation;
        $this->assignment = $assignment;
        $this->template = $template;
        $this->order = $order;
        $this->storage = $storage;
    }

    /**
     * @param TicketRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(TicketRequest $request)
    {
        // If we have lock_ticket submitted then release the lock on it
        if ($request->lock_ticket) {
            $this->ticket->unlockTicket($request->lock_ticket);
        }

        return view('admin::ticket.manager.index', [
            'pipelines' => $this->assignment->getPipelineList(),
            'statuses' => $this->statusRepo->getStatuses(),
            'categories' => $this->categoryRepo->getCategories(),
            'priorities' => Ticket::getPriorityList(),
            'assignments' => $this->assignment->getClientSideAssignmentList(),
            'multiMods' => MultiMod::where('is_active', 1)->orderBy('title', 'asc')->get(),
            'request' => $request,
        ]);
    }

    /**
     * @param TicketRequest $request
     * @param Ticket $ticket
     * @return mixed
     */
    public function view(TicketRequest $request, Ticket $ticket)
    {
        // Did we submit the form
        if ($request->submit || $request->submit_and_next) {
            // Apply Moderation
            $this->moderation->applyTicketModeration($request, $ticket);

            // Find the next one in line
            if ($request->submit_and_next) {
                $next = $this->ticketRepo->getNextTicket($ticket->id);

                if ($next) {
                    return redirect()->route('admin.ticket.manager.view', [
                        'id' => $next->id, 'request' => $request->hashedQuery
                    ]);
                } else {
                    return redirect()->route('admin.ticket.manager', [
                        'lock_ticket' => $ticket->id, $request->queryString
                    ]);
                }
            }

            // Redirect
            return back()->with('success', 'Ticket Updated.');
        }

        // Update users last viewed
        $this->ticket->addTicketViewer($ticket->id);

        // Lock ticket
        $this->ticket->lockTicket($ticket->id);

        return view('admin::ticket.manager.view', [
            'request' => $request,
            'ticket' => $ticket,
            'fromUsers' => $this->userRepo->getUsersByEmail(explode(',', $ticket->from_content)),
            'priorityClasses' => Ticket::getPriorityClasses(),
            'priorities' => Ticket::getPriorityList(),
            'multiMods' => MultiMod::where('is_active', 1)->orderBy('title', 'asc')->get(),
            'assignments' => $this->assignment->getAssignmentList(),
            'clientAssignments' => $this->assignment->getClientSideAssignmentList(),
            'emailTemplates' => $this->template->getTemplateList(),
            'emailTemplate' => $this->getViewEmailTemplate($ticket),
            'orderFiles' => $this->order->getAllOrderFiles($ticket->apprOrder),
            'assignTitle' => $this->ticketRepo->getTicketAssignTitle($ticket),
            'categories' => $this->categoryRepo->getCategories(),
            'statuses' => $this->statusRepo->getStatuses(),
            'relatedTickets' => $this->ticketRepo->getRelatedTickets($ticket),
            'ticketComments' => $this->ticket->getCommentsActivity($ticket->id),
            'viewing' => $this->ticket->getCurrentlyViewing($ticket->id),
            'ticketCategories' => $ticket->categories()->orderBy('tickets_category.name', 'asc')->get(),
            'ticketStatuses' => $ticket->statuses()->orderBy('tickets_status.name', 'asc')->get(),
            'participants' => $ticket->participants,
            'files' => $ticket->files()->orderBy('file_size', 'desc')->get(),
            'activity' => $ticket->activities()->orderBy('created_date', 'desc')->get(),
        ]);
    }

    /**
     * @param Request $request
     * @param TicketContentContract $contentRepo
     * @param Ticket $ticket
     * @return mixed
     */
    public function viewTicketContent(Request $request, TicketContentContract $contentRepo, Ticket $ticket)
    {
        $type = $request->get('type', 'html');

        $content = $contentRepo->getContent($ticket->id, $type);

        return response($content, 200)->header('Content-Type', 'text/html');
    }

    /**
     * @param Request $request
     * @param File $file
     * @return mixed
     */
    public function downloadDocument(Request $request, File $file)
    {
        // TODO: LM-2 make sure it works; currently not used

        if (!$file) {
            return redirect()->route('admin.ticket.manager')
                ->with('error', 'Sorry, That document was not found.');
        }

        $content = $this->getS3File($file);

        return response()->download($file->filename, $content);
    }

    /**
     * @param Request $request
     * @param TicketFileContract $fileRepo
     * @return mixed
     */
    public function viewImage(Request $request, TicketFileContract $fileRepo)
    {
        // TODO: LM-2 make sure it works; currently not used
        $content = $fileRepo->getImage($request);

        return response()->json($content);
    }

    /**
     * @param GetTicketsRequest $request
     * @return mixed
     */
    public function getTickets(GetTicketsRequest $request)
    {
        $rows = $this->ticketRepo->findTickets($request);

        return Datatables::of($rows)
            ->editColumn('id', function ($r) {
                return view('admin::ticket.manager.partials._ticket_checkbox', ['id' => $r->id]);
            })
            ->editColumn('helper', function ($r) {
                return view('admin::ticket.manager.partials._helper_column', ['row' => $r]);
            })
            ->editColumn('created', function ($r) {
                return $r->createdFormatDate;
            })
            ->editColumn('last_comment', function ($r) {
                return $r->last_comment ? date('m/d/Y g:i A', $r->last_comment) : '--';
            })
            ->editColumn('subject', function ($r) use ($request) {
                return view('admin::ticket.manager.partials._subject_line', [
                    'row' => $r,
                    'linkSubject' => true,
                    'hashedQuery' => $request->hashedQuery
                ]);
            })
            ->editColumn('order', function ($r) {
                return view('admin::ticket.manager.partials._order_line', ['row' => $r]);
            })
            ->editColumn('status', function ($r) {
                return view('admin::ticket.manager.partials._ticket_status', ['row' => $r, 'addLink' => true]);
            })
            ->editColumn('category', function ($r) {
                return view('admin::ticket.manager.partials._ticket_category', ['ticket' => $r, 'addLink' => true]);
            })
            ->editColumn('assigned', function ($r) {
                return view('admin::ticket.manager.partials._assigned', [
                    'row' => $r, 'addLink' => true, 'assignTitle' => $this->ticketRepo->getTicketAssignTitle($r)
                ]);
            })
            ->editColumn('time', function ($r) {
                return view('admin::ticket.manager.partials._ticket_time', ['row' => $r]);
            })
            ->editColumn('borrower', function ($r) {
                return $r->borrower;
            })
            ->editColumn('options', function ($r) use ($request) {
                return view('admin::ticket.manager.partials._options_button', [
                    'row' => $r, 'hashedQuery' => $request->hashedQuery
                ]);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * @param Request $request
     * @param ActivityContract $activityRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getActivityTab(Request $request, ActivityContract $activityRepo)
    {
        return view('admin::ticket.manager.templates._activity', [
            'rows' => $activityRepo->getActivity()
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getStatsTab(Request $request)
    {
        $pipeline = $this->assignment->getPipelineList();

        $stats = ['teams' => [], 'users' => []];
        foreach ($pipeline['teams'] as $teamId => $teamTitle) {
            $stats['teams'][] = $this->ticketRepo->getTeamStats($teamId, $teamTitle);
        }

        // Add unassigned
        $stats['teams']['unassigned'] = $this->ticketRepo->getTeamStats('unassigned', 'Unassigned');

        return view('admin::ticket.manager.templates._stats', ['pipelines' => $stats]);
    }

    /**
     * @param Request $request
     * @param Ticket $ticket
     * @return mixed
     */
    public function getCurrentlyViewingUpdate(Request $request, Ticket $ticket)
    {
        $viewing = $request->get('viewing', []); // 2,3,4
        $now = $this->ticket->getCurrentlyViewing($ticket->id);

        $left = [];
        $added = [];
        $leftNames = [];
        $addedNames = [];
        $viewingUsers = [];

        foreach ($now as $nowView) {
            $viewingUsers[] = $nowView->user_id;
        }

        // Get the ones that left/added
        foreach ($viewing as $userId) {
            if (!in_array($userId, $viewingUsers)) {
                // Not viewing anymore
                $left[] = $userId;
                $leftNames[] = userInfo($userId)->fullname;
            }
        }

        // Now add the new ones
        foreach ($now as $user) {
            if (!in_array($user->user_id, $viewing)) {
                // Not viewing anymore
                $added[] = $user->user_id;
                $addedNames[] = $user->fullname;
            }
        }

        $currently = view('admin::ticket.manager.templates._currently_viewing', [
            'row' => $ticket, 'viewing' => $now
        ])->render();

        $last = view('admin::ticket.manager.templates._last_viewed', ['ticket' => $ticket])->render();

        return response()->json([
            'left' => $left,
            'leftNames' => $leftNames,
            'addedNames' => $addedNames,
            'added' => $added,
            'viewing' => $viewingUsers,
            'currently' => $currently,
            'last' => $last
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function unlockTicket(Request $request)
    {
        $this->ticket->unlockTicket($request->id);

        return response()->json([]);
    }

    /**
     * @param Request $request
     * @param Ticket $ticket
     * @return mixed
     */
    public function closeTicket(Request $request, Ticket $ticket)
    {
        $this->ticket->closeTicket($ticket, $request->start);

        $redirect = '';
        if ($request->get('next')) {
            $next = $this->ticketRepo->getNextTicket($ticket->id);

            if ($next) {
                $redirect = route('admin.ticket.manager.view', [
                    'id' => $next->id,
                    'params' => $request->get('params')
                ]);

            } else {
                $redirect = route('admin.ticket.manager', [
                    'lock_ticket' => $ticket->id,
                    'params' => $request->get('params')
                ]);
            }
        }

        return response()->json(['redirect' => $redirect]);
    }

    /**
     * @param Request $request
     * @param Ticket $ticket
     * @return mixed
     */
    public function openTicket(Request $request, Ticket $ticket)
    {
        $this->ticket->openTicket($ticket);

        return response()->json([]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getEmailTemplate(Request $request)
    {
        $template = $this->template->getTemplate($request->templateId);

        $ticket = Ticket::find($request->ticketId);
        if ($ticket) {
            $template = $this->template->bindParams($template, $ticket->orderid);
        }

        return response()->json($template);
    }

    /**
     * @param Request $request
     * @param Comment $comment
     * @return mixed
     */
    public function getCommentContent(Request $request, Comment $comment)
    {
        $html = view('admin::ticket.manager.templates._ticket_comment', ['row' => $comment])->render();

        return response()->json(['html' => $html, 'title' => $comment->comment]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getTicketComments(Request $request)
    {
        $view = view('admin::ticket.manager.templates._ticket_comments', [
            'ticketComments' => $this->ticket->getCommentsActivity($request->get('id')),
        ])->render();

        return response()->json($view);
    }

    /**
     * @param Request $request
     * @param Comment $comment
     * @return mixed
     */
    public function setCommentVisibility(Request $request, Comment $comment)
    {
        $comment->is_public = $request->get('public');
        $comment->save();

        $view = view('admin::ticket.manager.partials._ticket_comment', [
            'row' => $comment,
        ])->render();

        return response()->json($view);
    }

    /**
     * @param Request $request
     * @param OrderContract $orderRepo
     * @return mixed
     */
    public function searchOrder(Request $request, OrderContract $orderRepo)
    {
        $orders = $orderRepo->searchOrders(trim($request->term));

        return response()->json($orders);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function removeParticipantUser(Request $request)
    {
        $this->ticket->removeParticipant($request->id, $request->ticketId);

        return response()->json([]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function findMentions(Request $request)
    {
        $users = $this->assignment->getMentions($request->typed);

        $html = view('admin::ticket.manager.templates._mentions', ['users' => $users])->render();

        return response()->json(['html' => $html]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function inlineCategoryEdit(Request $request)
    {
        $row = Ticket::find($request->get('pk'));

        $this->ticket->updateTicketCategory($row->id, $request->get('value'));

        $view = view('admin::ticket.manager.partials._ticket_category', [
            'row' => $row,
        ])->render();

        return response()->json($view);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function inlineStatusEdit(Request $request)
    {
        $row = Ticket::find($request->get('pk'));

        $this->ticket->updateTicketStatus($row->id, $request->get('value'));

        $view = view('admin::ticket.manager.partials._ticket_status', [
            'row' => $row,
        ])->render();

        return response()->json($view);
    }

    /**
     * @param Request $request
     * @param ActivityContract $activityRepo
     * @return mixed
     */
    public function inlineAssignEdit(Request $request, ActivityContract $activityRepo)
    {
        $ticket = Ticket::find($request->get('pk'));

        $result = $this->moderation->applyTicketModeration($request, $ticket, ['assign' => $request->get('value')]);

        // Save activity
        if (count($result['activity'])) {
            $activityRepo->addTicketActivity($ticket->id, implode('<br>', $result['activity']));
        }

        return response()->json($result);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getMultiModerationForm(Request $request)
    {
        $view = view('admin::ticket.manager.templates._index_multi_mod_options', [
            'assignments' => $this->assignment->getAssignmentList(),
            'statuses' => $this->statusRepo->getStatuses(),
            'categories' => $this->categoryRepo->getCategories(),
            'priorities' => Ticket::getPriorityList(),
            'checked' => $request->checked,
            'request' => $request,
        ])->render();

        return response()->json(['html' => $view, 'title' => 'Multi Ticket Moderation Options']);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function processMultiModerationForm(Request $request)
    {
        $result = $this->moderation->processMultiModeration($request);

        return response()->json($result);
    }

    /**
     * @param Request $request
     * @param MultiMod $mod
     * @return mixed
     */
    public function getMultiModRecord(Request $request, MultiMod $mod)
    {
        return response()->json($mod);
    }

    /**
     * @param Request $request
     * @param MultiMod $mod
     * @return mixed
     */
    public function getMultiModIndexForm(Request $request, MultiMod $mod)
    {
        $priorities = Ticket::getPriorityList();
        $assignments = $this->assignment->getAssignmentList();

        $participants = [];
        $rows = explode(',', $mod->add_participants);

        foreach ($rows as $row) {
            $participants[] = $this->assignment->getAssignUserName($assignments, $row);
        }

        $view = view('admin::ticket.manager.templates._index_multi_moderate_view', [
            'priorityTitle' => $priorities[$mod->set_priority] ?? config('constants.not_available'),
            'assignTitle' => $this->assignment->getAssignUserName($assignments, $mod->assign_to),
            'checked' => $request->checked,
            'participants' => $participants,
            'mod' => $mod
        ])->render();

        return response()->json(['html' => $view, 'title' => 'Multi-Moderation']);
    }

    /**
     * @param Request $request
     * @param MultiMod $mod
     * @return mixed
     */
    public function applyMultiModIndex(Request $request, MultiMod $mod)
    {
        $result = $this->moderation->applyMultiModeration($request, $mod);

        return response()->json($result);
    }

    // ------------------------

    protected function getS3File($file)
    {
        $s3 = $this->storage->make();

        return $s3->get(config('app.ticket_folder') . '/' . $file->tixid . '/' . $file->filename);
    }

    /**
     * @param $ticket
     * @return string
     */
    protected function getViewEmailTemplate($ticket)
    {
        $template = view('admin::ticket.manager.templates._email', [
            'phone' => $ticket->teamPhone,
        ])->render();

        // Figure out HTML Template
        if ($ticket->orderid) {
            $template = $this->template->bindParams($template, $ticket->orderid);
        }

        return str_replace(["\r", "\n"], '', $template);
    }
}