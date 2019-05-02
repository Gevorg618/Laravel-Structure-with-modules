<?php

namespace Modules\Admin\Repositories\Appraisal;


use App\Models\Appraisal\ApprAccountingAdmin;

/**
 * Class ApprAccountingAdminRepository
 * @package Modules\Admin\Repositories\Appraisal
 */
class ApprAccountingAdminRepository
{
    /**
     * @param $orderId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public static function getAdjustmentsPaymentHistory($orderId) {
        return ApprAccountingAdmin::where('order_id', $orderId)
            ->latest('created_date')->get();
    }
}