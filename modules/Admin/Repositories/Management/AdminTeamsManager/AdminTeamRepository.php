<?php

namespace Modules\Admin\Repositories\Management\AdminTeamsManager;

use App\Models\Management\AdminTeamsManager\AdminTeam;

class AdminTeamRepository
{
    /**
     * Object of AdminTeam class
     *
     * @var $adminTeam
     */
    private $adminTeam;

    /**
     * StatesRepository constructor.
     */
    public function __construct()
    {
        $this->adminTeam = new AdminTeam();
    }

    public function teamsByType($fromDate, $toDate, $type)
    {
        return $this->adminTeam->where('team_type', $type)->with(['teamMemberUsers' => function($teamMemberUsersQuery) use ($fromDate, $toDate) {
              
                $teamMemberUsersQuery->withCount(['dashboardDelayOrder' => function ($dashboardDelayOrderQuery) use ($fromDate, $toDate) {

                    $dashboardDelayOrderQuery->where('created_date', '>=', $fromDate)->where('created_date', '<=', $toDate);
                    
                }]);

                $teamMemberUsersQuery->with(['apprUserView' => function ($apprUserViewQuery) use ($fromDate, $toDate) {

                    $apprUserViewQuery->where('created_date', '>=', $fromDate)->where('created_date', '<=', $toDate);
                    
                }]);

                $teamMemberUsersQuery->withCount(['apprDashboardToTransfers' => function ($apprDashboardToTransfersQuery) use ($fromDate, $toDate) {

                    $apprDashboardToTransfersQuery->where('is_pause' , 0)->where('created_date', '>=', $fromDate)->where('created_date', '<=', $toDate);
                    
                }]);
        }]);
    }
    
    public function dailyTeamsByType($fromDate, $toDate, $type)
    {
        return $this->adminTeam->where('team_type', $type)->with(['apprDailyStats' => function ($query) use ($fromDate, $toDate) {
                $query->where('created_date', '>=', $fromDate)->where('created_date', '<=', $toDate);
        }]);
    }
    
}