<?php

namespace Modules\Admin\Repositories\Accounting;


use App\Models\Appraisal\OrderAddFee;

/**
 * Class OrderAddFeeRepository
 * @package Modules\Admin\Repositories
 */
class OrderAddFeeRepository
{
    /**
     * @param array $userIds
     * @param array $dateRange
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getFeeAmounts($userIds = [], $dateRange = [])
    {
        return OrderAddFee::select(\DB::raw("
            SUM(amount) as sum, apprid
        "))
            ->whereIn('apprid', $userIds)
            ->whereBetween('paid', $dateRange)
            ->groupBy('apprid')->get();
    }
}