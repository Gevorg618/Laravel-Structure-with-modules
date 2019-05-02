<?php

namespace Modules\Admin\Repositories\Appraisal;

use App\Models\Appraisal\ApprDashboardDailyStats;

class ApprDashboardDailyStatsRepository
{
    private $dashboardDailyStats;

    /**
     * ApprDashboardDailyStatsRepository constructor.
     */
    public function __construct()
    {
        $this->dashboardDailyStats = new ApprDashboardDailyStats();
    }

    /**
     * Number of total need to work on
     */
    public function needToWorkOn($date)
    {
        return $this->dashboardDailyStats->where('date_time' , '=>', $date);
    }

}