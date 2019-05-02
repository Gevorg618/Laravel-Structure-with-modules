<?php

namespace Modules\Admin\Repositories;


use App\Models\ApprQcOrderCorrection;

class ApprQcOrderCorrectionsRepository
{
    /**
     * @param $orderId
     * @return int
     */
    public function getCustomCorrections($orderId)
    {
        return ApprQcOrderCorrection::where('order_id', $orderId)->count();
    }
}