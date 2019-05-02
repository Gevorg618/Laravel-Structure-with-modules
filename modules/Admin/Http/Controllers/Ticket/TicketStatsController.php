<?php

namespace Modules\Admin\Http\Controllers\Ticket;

use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\Ticket\TicketStatsRequest;
use Modules\Admin\Repositories\Ticket\TicketRepository;
use Modules\Admin\Repositories\Users\UserRepository;
use Modules\Admin\Services\Ticket\AssignmentService;

class TicketStatsController extends AdminBaseController
{
    protected $ticketRepo;
    protected $assignment;
    protected $userRepo;

    /**
     * TicketStatsController constructor.
     * @param $ticketRepo
     */
    public function __construct(
        TicketRepository $ticketRepo,
        AssignmentService $assignment,
        UserRepository $userRepository
    )
    {
        $this->ticketRepo = $ticketRepo;
        $this->assignment = $assignment;
        $this->userRepo = $userRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::ticket.stats.index');
    }

    /**
     * @param TicketStatsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postGetData(TicketStatsRequest $request)
    {
        $fromUnix = strtotime($request->post('datefrom') . ' 00:00');
        $toUnix = strtotime($request->post('dateto') . ' 23:59');
        $totalCreated = $this->ticketRepo->getTotalTicketsCreatedByDate($fromUnix, $toUnix);
        $totalClosed = $this->ticketRepo->getTotalTicketsClosedByDate($fromUnix, $toUnix);
        $teams = $this->ticketRepo->getSupportTicketsTeamsForStats();
        $teamIds = $teams->pluck('id')->toArray();
        $closeAvgTime = $this->ticketRepo->getTicketCloseAVGTime($fromUnix, $toUnix);
        $users = $this->userRepo->getSupportTicketsUsersForStats();
        $userIds = $users->pluck('id')->toArray();
        $list = [];
        $closed = $this->ticketRepo->getTotalTicketsClosedByUsers($userIds, $fromUnix, $toUnix)
            ->pluck('count', 'closedid')->toArray();
        $totalSpent = $this->ticketRepo->getUserTotalTimeSpentInTickets($userIds, $fromUnix, $toUnix)
            ->pluck('time', 'closedid')->toArray();
        foreach($users as $user) {
            if(!isset($closed[$user->id])) {
                continue;
            }
            $list[$user->id] = array(
                'name' => $user->userData->firstname,
                'closed' => $closed[$user->id],
                'avg' => (($totalSpent[$user->id] / 60) / $closed[$user->id]),
                'totalspent' => $totalSpent[$user->id] / 60,
            );
        }

        $createdBusiness = $this->ticketRepo->getTotalTicketsCreatedDuringBusinessHours($fromUnix, $toUnix);
        $closedBusiness = $this->ticketRepo->getTotalTicketsClosedDuringBusinessHours($fromUnix, $toUnix);
        $createdTeamBusiness = $this->ticketRepo->getTeamTicketsCreatedDuringBusinessHours($fromUnix, $toUnix, $teamIds);
        $closedTeamBusiness = $this->ticketRepo->getTeamTicketsClosedDuringBusinessHours($fromUnix, $toUnix, $teamIds);
        $totalTeamsTicketCreated = $this->ticketRepo->getTotalTeamTicketsCreated($fromUnix, $toUnix, $teamIds)
            ->pluck('count', 'assignid')->toArray();
        $totalTeamsMaxClosedTime = $this->ticketRepo
            ->getTeamMAXCloseTime($teamIds, $fromUnix, $toUnix)
            ->pluck('max', 'assignid')->toArray();
        $teamsAvgCloseTime = $this->ticketRepo
            ->getTeamAVGCloseTime($userIds, $fromUnix, $toUnix)
            ->pluck('avg', 'assignid')->toArray();
        $totalTicketsClosedByTeam = $this->ticketRepo
            ->getTotalTicketsClosedByTeam($userIds, $fromUnix, $toUnix)
            ->pluck('count', 'assignid')->toArray();

        $totalDailyOrderRowsView = view($this->getViewName('total_daily_order_rows'), [
            'closedBusiness' => $closedBusiness,
            'createdBusiness' => $createdBusiness,
            'totalClosed' => $totalClosed,
            'totalCreated' => $totalCreated,
            'closeAvgTime' => $closeAvgTime,
        ])->render();
        $dailyOrderRowsView = view($this->getViewName('daily_order_rows'), [
            'teams' => $teams,
            'closedTeamBusiness' => $closedTeamBusiness,
            'createdTeamBusiness' => $createdTeamBusiness,
            'ticketRepo' => $this->ticketRepo,
            'from' => $fromUnix,
            'to' => $toUnix,
            'totalTeamsTicketCreated' => $totalTeamsTicketCreated,
            'totalTeamsMaxClosedTime' => $totalTeamsMaxClosedTime,
            'teamsAvgCloseTime' => $teamsAvgCloseTime,
            'totalTicketsClosedByTeam' => $totalTicketsClosedByTeam,
        ])->render();
        $dailyOrderRowsUserView = view($this->getViewName('daily_order_rows_user'), [
            'list' => $list
        ])->render();
        return response()->json([
            'total_daily_order_rows' => $totalDailyOrderRowsView,
            'daily_order_rows' => $dailyOrderRowsView,
            'daily_order_rows_user' => $dailyOrderRowsUserView,
        ]);
    }

    /**
     * @param $view
     * @return string
     */
    protected function getViewName($view)
    {
        return 'admin::ticket.stats.' . $view;
    }

    /**
     * @param TicketStatsRequest $request
     * @return bool
     */
    public function excelExport(TicketStatsRequest $request)
    {

        $fromUnix = strtotime($request->post('datefrom'));
        $toUnix = strtotime($request->post('dateto'));
        $teams = $this->ticketRepo->getSupportTicketsTeamsForStats();
        $users = $this->userRepo->getSupportTicketsUsersForStats();
        $userIds = $users->pluck('id')->toArray();
        $teamIds = $teams->pluck('id')->toArray();
        $list = [];
        $closed = $this->ticketRepo->getTotalTicketsClosedByUsers($userIds, $fromUnix, $toUnix)
            ->pluck('count', 'closedid')->toArray();
        $totalSpent = $this->ticketRepo->getUserTotalTimeSpentInTickets($userIds, $fromUnix, $toUnix)
            ->pluck('time', 'closedid')->toArray();
        foreach($users as $user) {
            if(!isset($closed[$user->id])) {
                continue;
            }
            $list[$user->id] = array(
                'name' => $user->userData->firstname,
                'closed' => $closed[$user->id],
                'avg' => (($totalSpent[$user->id] / 60) / $closed[$user->id]),
                'totalspent' => $totalSpent[$user->id] / 60,
            );
        }

        $createdTeamBusiness = $this->ticketRepo->getTeamTicketsCreatedDuringBusinessHours($fromUnix, $toUnix, $teamIds);
        $closedTeamBusiness = $this->ticketRepo->getTeamTicketsClosedDuringBusinessHours($fromUnix, $toUnix, $teamIds);
        $exportTeams = [];
        $exportUsers = [];
        foreach ($teams as $team) {
            $exportTeams[] = [
                'team_title' => $team->team_title,
                'closed' => isset($closedTeamBusiness['rows'][$team->id]) ? number_format($closedTeamBusiness['rows'][$team->id]['total']) : 0,
                'created' => isset($createdTeamBusiness['rows'][$team->id]) ? number_format($createdTeamBusiness['rows'][$team->id]['total']) : 0,
            ];
        }
        foreach ($list as $userId => $data) {
            $exportUsers[] = [
                'name' => $data['name'],
                'avg' => number_format($data['avg'], 2),
                'total_spent' => number_format($data['totalspent'], 2),
            ];
        }
        \Excel::create('export', function (LaravelExcelWriter $excel) use ($exportTeams, $exportUsers) {
            $excel->sheet('teams', function (LaravelExcelWorksheet $sheet) use ($exportTeams) {
                $sheet->fromArray($exportTeams);
            });
            $excel->sheet('users', function (LaravelExcelWorksheet $sheet) use ($exportUsers) {
                $sheet->fromArray($exportUsers);
            });

        })->download('xlsx');
        return true;
    }
}