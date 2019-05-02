<?php

namespace Modules\Admin\Repositories\Appraisal;


use App\Models\Appraisal\ApprCimPayment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CimPaymentRepository
 * @package Modules\Admin\Repositories\Appraisal
 */
class CimPaymentRepository
{
    /**
     * @param $from
     * @param $to
     * @param $type
     * @return Builder
     */
    public function getDailyBatchData($from, $to, $type)
    {
        return ApprCimPayment::with([
            'order',
        ])->select(['order_id', 'trans_id', 'amount', 'created_date'])
            ->where('is_success', 1)
            ->where('is_void', 0)->dailyBatchFilter($type)
            ->whereBetween('created_date', [
                strtotime($from . ' 00:00:00'),
                strtotime($to . ' 23:59:59'),
            ])->orderBy('created_date');
    }

    /**
     * @param $orderId
     * @return Collection|\Illuminate\Support\Collection|static[]
     */
    public static function getPaymentHistory($orderId) {
        return ApprCimPayment::where('order_id', $orderId)
            ->latest('created_date')->get();
    }
}