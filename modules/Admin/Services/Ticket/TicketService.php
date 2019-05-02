<?php

namespace Modules\Admin\Services\Ticket;

use App\Models\Ticket\Ticket;
use App\Models\Ticket\StatusRel;
use App\Models\Ticket\Comment;
use App\Models\Ticket\CategoryRel;
use App\Models\Ticket\Activity;
use App\Models\Ticket\Viewed;
use App\Models\Ticket\Status;
use App\Models\Ticket\Category;
use App\Models\Session;
use Modules\Admin\Contracts\Ticket\ActivityContract;

class TicketService
{
    protected $activity;

    public function __construct(ActivityContract $activity)
    {
        $this->activity = $activity;
    }

    /**
     * @param int $id
     * @param int $status
     * @param bool $activity
     * @return void
     */
    public function updateTicketStatus($id, $status, $activity = true)
    {
        StatusRel::where('ticket_id', $id)->delete();

        if ($status) { // Update
            StatusRel::insert(['ticket_id' => $id, 'status_id' => $status]);
        }

        // Add activity
        if ($status && $activity) {
            $ticketStatus = Status::find($status);
            $ticketStatus = $ticketStatus ? $ticketStatus->name : config('constants.not_available');

            $this->activity->addTicketActivity($id, sprintf('Updated Status To %s', $ticketStatus));
        }
    }

    /**
     * @param int $id
     * @param int $category
     * @param bool $activity
     * @return void
     */
    public function updateTicketCategory($id, $category, $activity = true)
    {
        CategoryRel::where('ticket_id', $id)->delete();

        if ($category) { // Update
            CategoryRel::create(['ticket_id' => $id, 'category_id' => $category]);
        }

        // Add activity
        if ($category && $activity) {
            $ticketCategory = Category::find($category);
            $ticketCategory = $ticketCategory ? $ticketCategory->name : config('constants.not_available');

            $this->activity->addTicketActivity($id, sprintf('Updated Category To %s', $ticketCategory));
        }
    }

    /**
     * @param $id
     * @return array
     */
    public function getCurrentlyViewing($id)
    {
        $time = time() - (60 * 5); // 5 min cut off

        $sessions = Session::where([
            ['user_id', '>', 0],
            ['ticket_id', '=', $id],
            ['last_click', '>=', $time],
        ])->get();

        $rows = [];

        // Load any users that are viewing the an order with the current ticket assigned to it
        foreach ($sessions as $session) {
            if ($session->order_id) {
                $orders = Session::where([
                    ['user_id', '>', 0],
                    ['order_id', '=', $session->order_id],
                    ['last_click', '>=', $time],
                ])->get();

                if ($orders->count()) {
                    foreach ($orders as $order) {
                        $rows[$order->user_id] = $order;
                    }
                }
            }

            $rows[$session->user_id] = $session;
        }

        return $rows;
    }

    /**
     * @param int $id
     * @param array $userIds
     * @return array
     */
    public function updateTicketParticipants($id, $userIds)
    {
        $names = [];

        // Update
        if ($userIds) {
            foreach ($userIds as $userId) {
                $userId = str_replace('user_', '', $userId);
                $user = userInfo($userId);
                $ticket = Ticket::find($id);

                if ($user && $ticket) {
                    $names[$userId] = $user->fullname;

                    $ticket->participates()->firstOrNew(['user_id' => $userId])->save();
                }
            }
        }

        return $names;
    }

    /**
     * @param $userId
     * @param $id
     * @return void
     */
    public function removeParticipant($userId, $id)
    {
        $ticket = Ticket::find($id);

        if ($ticket) {
            // Remove
            $result = $ticket->participates()->where('user_id', $userId)->delete();

            if ($result) {
                // Add activity
                $this->activity->addTicketActivity(
                    $ticket->id, sprintf('Removed Participant %s', userInfo($userId)->fullname)
                );
            }
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCommentsActivity($id)
    {
        $comments = Comment::where('ticket_id', $id)->orderBy('created_date', 'desc')->get();
        $activity = Activity::where('ticket_id', $id)->orderBy('created_date', 'desc')->get();

        // Merge & Sort
        return $comments->merge($activity)->sortBy('created_date');
    }

    /**
     * @param int $id
     * @return void
     */
    public function lockTicket($id)
    {
        // Set everything i have locked as open
        Ticket::where('id', admin()->id)->update(['locked_by' => 0, 'locked_date' => 0]);

        // Set This one locked
        Ticket::where('id', $id)->update(['locked_by' => admin()->id, 'locked_date' => time()]);
    }

    /**
     * @param $id
     * @return void
     */
    public function unlockTicket($id)
    {
        $ticket = Ticket::where([
            ['id', '=', $id],
            ['locked_by', '>', '0']
        ])->first();

        if ($ticket) {
            $ticket->update(['locked_by' => 0, 'locked_date' => 0]);

            $this->activity->addTicketActivity($id, 'Unlocked Ticket');
        }
    }

    /**
     * @param Ticket $ticket
     * @return void
     */
    public function openTicket($ticket)
    {
        $ticket->update(['closedid' => 0, 'closed_date' => 0]);

        $this->activity->addTicketActivity($ticket->id, sprintf('Opened Ticket'));
    }

    /**
     * @param Ticket $ticket
     * @param int $start
     * @return void
     */
    public function closeTicket($ticket, $start)
    {
        $ticket->closedid = admin()->id;
        $ticket->closed_date = time();

        if ($start) {
            $ticket->close_start = $start;
            $ticket->close_end = time();
        }

        $ticket->save();

        $this->activity->addTicketActivity($ticket->id, 'Closed Ticket');
    }

    /**
     * @param int $id
     * @return void
     */
    public function addTicketViewer($id)
    {
        $lastViewedTicket = Viewed::where([
            ['ticket_id', '=', $id],
            ['user_id', '=', admin()->id],
        ]);

        if ($lastViewedTicket->count()) {
            $lastViewedTicket->update(['created_date' => time()]);
        } else {
            Viewed::create([
                'ticket_id' => $id, 'user_id' => admin()->id, 'created_date' => time()
            ]);
        }
    }
}