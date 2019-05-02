<?php

namespace Modules\Admin\Repositories\Statistics;
use App\Models\Appraisal\ApprDashboardTransferTo;

class DashboardStatisticsTransferToRepository
{

    /**
     * Object of ApprDashboardTransferTo class
     *
     * @var apprDashboardTransferTo
     */
    private $apprDashboardTransferTo;

    /**
     * DashboardStatisticsTransferToRepository constructor.
     *
     */
    public function __construct()
    {
        $this->apprDashboardTransferTo = new ApprDashboardTransferTo();
    }

    /**
     * @return collection
     */
    public function transferred($fromDate, $toDate)
    {
        return $this->apprDashboardTransferTo->with(['apprOrders'])->where('is_pause', 0)->where('created_date', '>=', $fromDate)->where('created_date', '<=', $toDate);
    }
}