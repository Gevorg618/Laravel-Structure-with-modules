<?php

namespace Modules\Admin\Repositories\Statistics;
use Modules\Admin\Repositories\Ticket\OrderRepository;
use Yajra\DataTables\Datatables;
use Modules\Admin\Repositories\Customizations\TypesRepository;
use Modules\Admin\Repositories\Management\AdminTeamsManager\AdminTeamRepository;
use Modules\Admin\Repositories\Users\UserRepository;
use App\Models\Users\User;
use Modules\Admin\Repositories\Statistics\DashboardStatisticsTransferToRepository;

class DashboardStatisticsRepository
{

    /**
     * the team type name
     * 
     * @var string
     */
    private $teamType = 'appr';

    /**
     * Object of OrderRepository class
     *
     * @var orderRepo
     */
    private $orderRepo;

    /**
     * Object of TypesRepository class
     *
     * @var typeRepo
     */
    private $typeRepo;

    /**
     * Object of AdminTeamRepository class
     *
     * @var adminTeamRepo
     */
    private $adminTeamRepo;

    /**
     * Object of UserRepository class
     *
     * @var userRepo
     */
    private $userRepo;

    /**
     * Object of DashboardStatisticsTransferToRepository class
     *
     * @var dashboardStatisticsTransferToRepo
     */
    private $dashboardStatisticsTransferToRepo;

    /**
     * DashboardStatisticsRepository constructor.
     *
     */
    public function __construct()
    {
        $this->orderRepo = new OrderRepository();
        $this->typeRepo = new TypesRepository();
        $this->adminTeamRepo = new AdminTeamRepository();
        $this->userRepo = new UserRepository();
        $this->dashboardStatisticsTransferToRepo = new DashboardStatisticsTransferToRepository();
    }

    /**
     * get  statistics more info
     *
     * @param date $fromDate
     * @param date $toDate
     * @param array $clients
     * 
     * @return response 
     */
    public function statistics($fromDate, $toDate, $type = null, $skip = null, $take = null)
    {
       
       $response = false;

       switch ($type) {
            case 'team':
                    $response = $this->teamDataTables($fromDate, $toDate, $skip , $take);
               break;
            case 'users':
                    $response = $this->usersDataTables($fromDate, $toDate, $skip , $take);
               break;
            case 'transferred_orders':
                    $response = $this->transferredOrdersDataTables($fromDate, $toDate, $skip , $take);
               break;     
            case 'daily_stats':
                    $response = $this->dailyDataTables($fromDate, $toDate, $skip , $take);
                break;    
       }

       return $response;
    }


     /**
     * get place orders for dataTable
     *
     * @return array $teamDataTables
     */
    public function teamDataTables($fromDate, $toDate, $skip , $take)
    {

        $teams = $this->adminTeamRepo->teamsByType(strtotime($fromDate), strtotime($toDate), $this->teamType);

        $teamsRawCount = $teams->count();

        $teams = $teams->skip((int)$skip)->take((int)$take);
      
        $placedOrdersDataTables = Datatables::of($teams)
                ->editColumn('team_title', function ($team) {
                    return $team->team_title;
                })
                ->editColumn('worked_on', function ($team) {
                    
                    $workedOnCount = 0;
                    foreach ($team->teamMemberUsers as $user) {
                        $workedOnCount += $user->dashboard_delay_order_count;
                    }
                    return $workedOnCount;
                })
                ->editColumn('avg_adjusted', function ($team) {

                    $total = 0;

                    foreach ($team->teamMemberUsers as $user) {

                        foreach ($user->apprUserView as $apprUserView) {
                                $total += ($apprUserView->adjusted_time/60);
                        }

                    }

                    return $total ? number_format ( round($total/count($team->teamMemberUsers), 2), 2 ): 0 ;
                     

                })
                ->editColumn('avg_total', function ($team) {
                    $total = 0;

                    foreach ($team->teamMemberUsers as $user) {

                        foreach ($user->apprUserView as $apprUserView) {
                                $total += ($apprUserView->total_time/60);
                        }

                    }

                    return $total ? number_format( round($total/count($team->teamMemberUsers), 2) , 2 ): 0 ;
                })
                ->editColumn('adjusted', function ($team) {
                   
                   $total = 0;

                    foreach ($team->teamMemberUsers as $user) {

                        foreach ($user->apprUserView as $apprUserView) {
                                $total += ($apprUserView->adjusted_time/60);
                        }

                    }

                    return $total ? number_format(round($total/60, 2), 2): 0 ;

                })
                ->editColumn('total', function ($team) {
                    $total = 0;

                    foreach ($team->teamMemberUsers as $user) {

                        foreach ($user->apprUserView as $apprUserView) {
                                $total += ($apprUserView->total_time/60);
                        }

                    }

                    return $total ? number_format(round($total/60, 2), 2): 0 ;
                })
                ->editColumn('transferred', function ($team) {

                    $transferedCount = 0;

                    foreach ($team->teamMemberUsers as $user) {
                        $transferedCount += $user->appr_dashboard_to_transfers_count;
                    }

                    return $transferedCount;
                })
                ->editColumn('delayed', function ($team) {
                    $workedOnCount = 0;
                    foreach ($team->teamMemberUsers as $user) {
                        $workedOnCount += $user->dashboard_delay_order_count;
                    }
                    return $workedOnCount;
                })
                ->setTotalRecords($teamsRawCount)
                ->make(true);
                
        return $placedOrdersDataTables;
    }

    /**
     * get place orders for dataTable
     *
     * @return array $teamDataTables
     */
    public function usersDataTables($fromDate, $toDate, $skip , $take)
    {

        $users = $this->userRepo->usersForDashboardStats(strtotime($fromDate), strtotime($toDate), User::USER_TYPE_ADMIN);
        
        $usersRawCount = $users->count();

        $users = $users->skip((int)$skip)->take((int)$take);
      
        return  Datatables::of($users)
                ->editColumn('name', function ($user) {
                    return $user->getFullNameAttribute();
                })
                ->editColumn('team', function ($user) {
                    return $user->teamMembers->first() ? $user->teamMembers->first()->team_title: 'N/A';
                })
                ->editColumn('worked_on', function ($user) {
                    return $user->dashboard_delay_order_count;
                })
                ->editColumn('avg_adjusted', function ($user) {

                    $total = 0;

                    foreach ($user->apprUserView as $apprUserView) {
                            $total += ($apprUserView->adjusted_time/60);
                    }

                    return $total ? number_format ( round($total/count($user->teamMemberUsers), 2), 2 ): 0 ;
                     

                })
                ->editColumn('avg_total', function ($user) {
                    $total = 0;

                    foreach ($user->apprUserView as $apprUserView) {
                            $total += ($apprUserView->total_time/60);
                    }

                    return $total ? number_format( round($total/count($user->teamMemberUsers), 2) , 2 ): 0 ;
                })
                ->editColumn('adjusted', function ($user) {
                   
                   $total = 0;

                    foreach ($user->apprUserView as $apprUserView) {
                            $total += ($apprUserView->adjusted_time/60);
                    }

                    return $total ? number_format(round($total/60, 2), 2): 0 ;

                })
                ->editColumn('total', function ($user) {
                    $total = 0;

                    foreach ($user->apprUserView as $apprUserView) {
                            $total += ($apprUserView->total_time/60);
                    }

                    return $total ? number_format(round($total/60, 2), 2): 0 ;
                })
                ->editColumn('transferred', function ($user) {
                    return  $user->appr_dashboard_to_transfers_count;
                })
                ->editColumn('delayed', function ($user) {
                    return  $user->dashboard_delay_order_count;

                })
                ->setTotalRecords($usersRawCount)
                ->make(true);
    }

    /**
     *
     * @return array
     */
    public function transferredOrdersDataTables($fromDate, $toDate, $skip , $take)
    {
        $transferred  = $this->dashboardStatisticsTransferToRepo->transferred(strtotime($fromDate), strtotime($toDate));

        $transferredRawCount = $transferred->count();

        $transferred = $transferred->skip((int)$skip)->take((int)$take);
      
        return  Datatables::of($transferred)
                ->editColumn('id', function ($transfer) {
                    return $transfer->apprOrders->id;
                })
                ->editColumn('address', function ($transfer) {
                    return $transfer->apprOrders->getAddressAttribute();
                })
                ->editColumn('date', function ($transfer) {
                    return $transfer->apprOrders->date_delivered;
                })
                ->editColumn('team', function ($transfer) {
                    return $transfer->apprOrders ? $transfer->apprOrders->getTeamTitle() : 'N/A';
                })
                ->editColumn('from_user', function ($transfer) {
                    return $transfer->fromUser ? $transfer->fromUser->getFullNameAttribute() : 'N/A';
                })
                ->editColumn('to_user', function ($transfer) {
                    return $transfer->toUser ? $transfer->toUser->getFullNameAttribute() : 'N/A';
                })
                ->setTotalRecords($transferredRawCount)
                ->make(true);
    }

    /**
     *
     * @return array
     */
    public function dailyDataTables($fromDate, $toDate, $skip , $take)
    {
        $dailyTeams = $this->adminTeamRepo->dailyTeamsByType(strtotime($fromDate), strtotime($toDate), $this->teamType);

        $dailyTeamsRawCount = $dailyTeams->count();

        $dailyTeams = $dailyTeams->skip((int)$skip)->take((int)$take);

        return  Datatables::of($dailyTeams)
                ->editColumn('team', function ($dailyTeam) {
                    return $dailyTeam->team_title;
                })
                ->editColumn('company_pipeline', function ($dailyTeam) {
                    $pipeline = 0;

                    foreach($dailyTeam->apprDailyStats as $apprDailyStats) {
                        $pipeline += $apprDailyStats->pipeline_start;
                    }

                    return $pipeline;
                })
                ->editColumn('status_select_count', function ($dailyTeam) {
                    $status = 0;

                    foreach($dailyTeam->apprDailyStats as $apprDailyStats) {
                        $status += $apprDailyStats->status_start;
                    }
                    
                    return $status;
                })
                ->editColumn('to_work_on', function ($dailyTeam) {
                    $pipeline = 0;

                    foreach($dailyTeam->apprDailyStats as $apprDailyStats) {
                        $pipeline += $apprDailyStats->pipeline_start;
                    }

                    $status = 0;

                    foreach($dailyTeam->apprDailyStats as $apprDailyStats) {
                        $status += $apprDailyStats->status_start;
                    }

                    return $pipeline ? '% '. number_format( ($status*100/$pipeline), 2) : '% '. 0;
                })
                ->setTotalRecords($dailyTeamsRawCount)
                ->make(true);
    }
}
