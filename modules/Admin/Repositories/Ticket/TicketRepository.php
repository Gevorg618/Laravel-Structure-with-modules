<?php

namespace Modules\Admin\Repositories\Ticket;

use App\Models\Management\AdminTeamsManager\AdminTeam;
use App\Models\AdminTeamMember;
use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\Contracts\Ticket\TicketContract;
use App\Models\Ticket\Ticket;
use Modules\Admin\Helpers\DateHelper;

/**
 * Class TicketRepository
 * @package Modules\Admin\Repositories
 */
class TicketRepository implements TicketContract
{
    private $ticket;

    /**
     * TicketRepository constructor.
     *
     * @param Ticket $ticket
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * @return int
     */
    public function getTicketsCount()
    {
        return Ticket::where('is_email_import', 1)
            ->where('created_date', '>=', strtotime('today'))
            ->where('created_date', '<=', strtotime('tomorrow'))
            ->count();
    }

    /**
     * @param int $teamId
     * @param string $teamTitle
     * @return array
     */
    public function getTeamStats($teamId, $teamTitle)
    {
        $oldest = Ticket::ofClosed('open')->ofGroups([$teamId])->orderBy('created_date', 'asc')->first();

        return [
            'title' => $teamTitle,
            'open' => Ticket::ofClosed('open')->ofGroups([$teamId])->count(),
            'closed_today' => Ticket::ofClosed('closed')
                ->ofGroups([$teamId])
                ->OfClosedPeriod(strtotime('today'), strtotime('today 23:59:59'))
                ->count(),
            'closed_yesterday' => Ticket::ofClosed('closed')
                ->ofGroups([$teamId])
                ->OfClosedPeriod(strtotime('yesterday'), strtotime('yesterday 23:59:59'))
                ->count(),
            'oldest_opened' => $oldest ? date('Y-m-d H:i:s', $oldest->created_date) : '',
        ];
    }

    /**
     * @param array|Ticket $ticketData
     * @return string|null
     */
    public function getTicketAssignTitle($ticketData)
    {
        // Make sure we have assign type and assign id
        if (!$ticketData['assigntype'] || !$ticketData['assignid']) {
            return null;
        }

        if ($ticketData['assigntype'] == config('constants.assign_type_team')) {
            $teams = adminTeams();

            if (isset($teams[$ticketData['assignid']])) {
                return $teams[$ticketData['assignid']];
            }
        } elseif ($ticketData['assigntype'] == config('constants.assign_type_user')) {
            $user = userInfo($ticketData['assignid']);

            if ($user) {
                return $user->fullname;
            }
        }

        return null;
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNextTicket($id)
    {
        return $this->ticket::where([
            ['closedid', '=', 0],
            ['id', '>', $id],
            ['locked_by', '=', 0],
        ])->whereNotIn('id', [$id])
            ->orderBy('created_date', 'asc')
            ->first();
    }

    /**
     * @param $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findTickets($request)
    {
        return $this->ticket::select(
            'tickets.*',
            \DB::raw('COUNT(tickets_comments.id) AS total_comments'),
            \DB::raw('MAX(tickets_comments.created_date) AS last_comment')
        )
            ->leftJoin('tickets_comments', 'tickets_comments.ticket_id', '=', 'tickets.id')
            ->withCategories($request->get('category', false))
            ->withStates(getStatesByRegions($request->timezone))
            ->withStatuses($request->get('status', false))
            ->ofPriority($request->get('priority', false))
            ->ofSearch($request->search['value'])
            ->ofClosed($request->get('open_or_close', false))
            ->ofGroups($request->get('grouped', false))
            ->groupBy('tickets.id');
    }

    /**
     * @param Ticket $ticket
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRelatedTickets($ticket, $limit = 500)
    {
        return $this->ticket::where([
            ['id', '!=', $ticket->id],
            ['closed_date', '=', 0],
        ])->where(function ($q) use ($ticket) {
            $q->where('orderid', '=', $ticket->orderid)
                ->orWhere([
                    ['subject', '=', $ticket->subject],
                    ['from_content', '=', $ticket->from_content],
                ]);
        })->limit($limit)->get();
    }

    /**
     * @param $fromDate
     * @param $toDate
     * @return int
     */
    public function getTotalTicketsCreatedByDate($fromDate, $toDate)
    {
        return Ticket::orWhereBetween('created_date', [$fromDate, $toDate])->count();
    }

    /**
     * @param $fromDate
     * @param $toDate
     * @return int
     */
    public function getTotalTicketsClosedByDate($fromDate, $toDate)
    {
        return Ticket::orWhereBetween('closed_date', [$fromDate, $toDate])->count();
    }

    /**
     * @return array|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function getSupportTicketsTeamsForStats()
    {
        if (admin()) {
            return AdminTeam::orderBy('team_title')->get();
        }
        if (auth()->user()->admin_team) {
            $adminUserTeam = AdminTeamMember::find('id', auth()->id());
            return AdminTeam::find($adminUserTeam->team_id);
        }
        return [];
    }


    public function getTotalTicketsClosedByUsers($userIds = [], $from, $to)
    {
        return Ticket::select(\DB::raw('count(id) as count, closedid'))
            ->whereIn('closedid', $userIds)
            ->whereBetween('closed_date', [$from, $to])->get();

    }

    /**
     * @param array $ids
     * @param $from
     * @param $to
     * @return Collection|static[]
     */
    public function getUserTotalTimeSpentInTickets($ids = [], $from, $to)
    {
        return Ticket::select(\DB::raw('SUM( close_end - close_start ) AS time, closedid'))
            ->whereIn('closedid', $ids)
            ->whereBetween('closed_date', [$from, $to])
            ->groupBy('closedid')
            ->get();
    }

    /**
     * @param $from
     * @param $to
     * @return array
     */
    public function getTotalTicketsCreatedDuringBusinessHours($from, $to)
    {
        $days = DateHelper::getTicketDaysArray($from, $to);
        $tickets = new Ticket();
        if (!count($days)) {
            return ['total' => 0];
        }

        // Loop each date and get the amount of tickets created

        foreach ($days as $day) {
            $tickets = $tickets->orWhereBetween('created_date',
                [strtotime($day['fromhuman']), strtotime($day['tohuman'])]);
        }

        return ['total' => $tickets->count()];
    }

    /**
     * @param $from
     * @param $to
     * @return array
     */
    public function getTotalTicketsClosedDuringBusinessHours($from, $to)
    {
        $days = DateHelper::getTicketDaysArray($from, $to);
        $tickets = new Ticket();

        if (!count($days)) {
            return ['total' => 0];
        }

        // Loop each date and get the amount of tickets created
        foreach ($days as $day) {
            $tickets = $tickets->orWhereBetween('closed_date',
                [strtotime($day['fromhuman']), strtotime($day['tohuman'])]);
        }


        return ['total' => $tickets->count()];
    }

    /**
     * @param $from
     * @param $to
     * @param array $teamIds
     * @return array
     */
    public function getTeamTicketsCreatedDuringBusinessHours($from, $to, $teamIds = [])
    {
        $days = DateHelper::getTicketDaysArray($from, $to);
        $rows = [];

        $tickets = Ticket::select(\DB::raw('count(id) as count, assignid'))
            ->where('assigntype', 'T')
            ->where('assignid', '<>', 0)
            ->whereIn('assignid', $teamIds)
            ->where(function ($query) use ($days) {
                if ($days && count($days)) {
                    foreach ($days as $day) {
                        $query->orWhereBetween('created_date', [strtotime($day['fromhuman']), strtotime($day['tohuman'])]);
                    }
                }
            });
        // Loop each date and get the amount of tickets created

        $tickets = $tickets->groupBy('assignid')->get()
            ->pluck('count', 'assignid');
        foreach ($tickets as $assignId => $count) {
            $rows[$assignId]['total'] = $count;
        }

        return ['rows' => $rows];
    }

    /**
     * @param $from
     * @param $to
     * @param array $teamIds
     * @return Collection|static[]
     */
    public function getTotalTeamTicketsCreated($from, $to, $teamIds = [])
    {
        return Ticket::select(\DB::raw('count(id) as count, assignid'))
            ->whereBetween('created_date', [$from, $to])
            ->where('assigntype', 'T')
            ->whereIn('assignid', $teamIds)
            ->groupBy('assignid')->get();
    }

    /**
     * @param $from
     * @param $to
     * @param array $teamIds
     * @return array
     */
    public function getTeamTicketsClosedDuringBusinessHours($from, $to, $teamIds = [])
    {
        $days = DateHelper::getTicketDaysArray($from, $to);
        $rows = [];

        $tickets = Ticket::select(\DB::raw('count(id) as count, assignid'))
            ->where('assigntype', 'T')
            ->whereIn('assignid', $teamIds)
            ->where(function ($query) use ($days) {
                if ($days) {
                    foreach ($days as $day) {
                        $query->orWhereBetween('closed_date', [strtotime($day['fromhuman']), strtotime($day['tohuman'])]);
                    }
                }
            });
        // Loop each date and get the amount of tickets closed

        $tickets = $tickets->groupBy('assignid')->get()
            ->pluck('count', 'assignid');
        foreach ($tickets as $assignId => $count) {
            $rows[$assignId]['total'] = $count;
        }

        return ['rows' => $rows];
    }

    /**
     * @param $ids
     * @param $from
     * @param $to
     * @return Collection|static[]
     */
    public function getTotalTicketsClosedByTeam($ids, $from, $to)
    {
        return Ticket::select(\DB::raw('count(id) as count, assignid'))
            ->where('assigntype', 'T')
            ->whereIn('assignid', $ids)
            ->whereBetween('closed_date', [$from, $to])->get();
    }

    /**
     * @param $from
     * @param $to
     * @return int|mixed
     */
    public function getTicketCloseAVGTime($from, $to)
    {
        $ticket = Ticket::select(\DB::raw('AVG( 
            TIMESTAMPDIFF(
                MINUTE , FROM_UNIXTIME(created_date), FROM_UNIXTIME(closed_date) 
            ) 
        ) AS avg'))
            ->where('closedid', '>', 0)
            ->whereBetween('closed_date', [$from, $to])
            ->first();
        if (!$ticket) {
            return 0;
        }
        return $ticket->avg;
    }

    /**
     * @param $ids
     * @param $from
     * @param $to
     * @return Collection|static[]
     */
    public function getTeamAVGCloseTime($ids, $from, $to)
    {
        return Ticket::select(\DB::raw(
            'AVG( TIMESTAMPDIFF(MINUTE , FROM_UNIXTIME(created_date), FROM_UNIXTIME(closed_date) ) ) AS avg'
        ))->where('assigntype', 'T')
            ->whereIn('assignid', $ids)
            ->whereBetween('closed_date', [$from, $to])
            ->where('closedid', '>', 0)
            ->groupBy('assignid')->get();
    }

    /**
     * @param $ids
     * @param $from
     * @param $to
     * @return Collection|static[]
     */
    public function getTeamMAXCloseTime($ids, $from, $to)
    {
        return Ticket::select(\DB::raw(
            'MAX( TIMESTAMPDIFF(MINUTE , FROM_UNIXTIME(created_date), FROM_UNIXTIME(closed_date) ) ) AS max, assignid'
        ))->whereBetween('closed_date', [$from, $to])
            ->where('closedid', '>', 0)
            ->whereIn('assignid', $ids)
            ->where('assigntype', 'T')
            ->groupBy('assignid')->get();
    }
}