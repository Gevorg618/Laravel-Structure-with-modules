<?php

namespace Modules\Admin\Repositories\Accounting;

use App\Models\Appraisal\Order;
use App\Models\Appraisal\AppraiserPayment;

class AppraiserPaymentRepository
{
    /**
     * @param $dateFrom
     * @param $dateTo
     * @return int
     */
    public function getTotalAmountForCheckPaymentsReport($dateFrom, $dateTo)
    {
        return AppraiserPayment::whereBetween('date_sent', [
            date('Y-m-d', strtotime($dateFrom)),
            date('Y-m-d', strtotime($dateTo)),
        ])->groupBy('checknum')->sum('checkamount');
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getTotalCompletedChecksForCheckPaymentsReport($dateFrom, $dateTo)
    {
        return AppraiserPayment::select(\DB::raw(
            "orderid, split_amount as amount"
        ))->leftJoin('appr_order', 'appr_order.id', '=', 'appraiser_payments.orderid')
            ->whereHas('order', function ($query) {
            return $query->where('status', Order::STATUS_APPRAISAL_COMPLETED);
        })
            ->whereBetween('date_sent', [
                date('Y-m-d', strtotime($dateFrom)),
                date('Y-m-d', strtotime($dateTo)),
            ])->get();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getTotalInProgressChecksForCheckPaymentsReport($dateFrom, $dateTo)
    {
        return AppraiserPayment::select(\DB::raw(
            "orderid, split_amount as amount"
        ))->leftJoin('appr_order', 'appr_order.id', '=', 'appraiser_payments.orderid')
            ->whereHas('order', function ($query) {
            return $query->whereNotIn('status', [
                Order::STATUS_APPRAISAL_COMPLETED,
                Order::STATUS_TEMP,
                Order::STATUS_CANCELLED,
                Order::STATUS_AWAITING_CLIENT_APPROVAL
            ]);
        })->whereBetween('date_sent', [
            date('Y-m-d', strtotime($dateFrom)),
            date('Y-m-d', strtotime($dateTo)),
        ])->get();
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getAppraiserCheckPaymentsById($userId)
    {
        return AppraiserPayment::where('apprid', $userId)
            ->latest('paid')->get();
    }
}