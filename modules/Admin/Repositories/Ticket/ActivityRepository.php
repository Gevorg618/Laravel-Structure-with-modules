<?php

namespace Modules\Admin\Repositories\Ticket;

use Modules\Admin\Contracts\Ticket\ActivityContract;
use App\Models\Ticket\Activity;

class ActivityRepository implements ActivityContract
{
    private $activity;

    /**
     * ActivityRepository constructor.
     *
     * @param Activity $activity
     */
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * @param int $ticketId
     * @param string $message
     * @return void
     */
    public function addTicketActivity($ticketId, $message)
    {
        $this->activity->insert([
            'ticket_id' => $ticketId,
            'message' => $message,
            'created_by' => admin()->id,
            'created_date' => time()
        ]);
    }

    /**
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActivity($limit = 100)
    {
        $fullName = "CONCAT(user_data.firstname, ' ', user_data.lastname)";

        return $this->activity->select(
            'tickets_activity.*',
            \DB::raw("IF (user_data.firstname IS NULL, user.email, " . $fullName . ") as fullname")
        )
            ->join('user', 'user.id', '=', 'tickets_activity.created_by')
            ->leftJoin('user_data', 'user_data.user_id', '=', 'user.id')
            ->orderBy('created_date', 'desc')
            ->limit($limit)
            ->get();
    }
}