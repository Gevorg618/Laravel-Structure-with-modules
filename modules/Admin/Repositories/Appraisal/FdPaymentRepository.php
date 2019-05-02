<?php

namespace Modules\Admin\Repositories\Appraisal;

use App\Models\Appraisal\Order;
use App\Models\Customizations\Status;
use App\Models\Appraisal\ApprFDPayment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class FdPaymentRepository
 * @package Modules\Admin\Repositories\Appraisal
 */
class FdPaymentRepository
{
    const CHARGE = 'CHARGE';
    const REFUND = 'REFUND';
    /**
     * @param $from
     * @param $to
     * @param $type
     * @return Builder
     */
    public function getDailyBatchData($from, $to, $type)
    {
        return ApprFDPayment::with([
            'order'
        ])->select(['order_id', 'trans_id', 'amount', 'created_date'])
            ->where('is_success', 1)
            ->where('is_void', 0)
            ->whereBetween('created_date', [
                strtotime($from . ' 00:00:00'),
                strtotime($to . ' 23:59:59')
            ])->dailyBatchFilter($type)
            ->orderBy('created_date');
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getCreditCardsCompletedForCreditCardReceiptReport($dateFrom, $dateTo)
    {
        return ApprFDPayment::select(\DB::raw(
            "COUNT(appr_order.id) as total_orders,
            SUM(amount) as total_amount"
        ))->leftJoin('appr_order', 'appr_order.id', '=', 'appr_fd_payments.order_id')
            ->where('is_success', 1)
            ->where('ref_type', self::CHARGE)
            ->where('status', Order::STATUS_APPRAISAL_COMPLETED)
            ->whereBetween('created_date', [
                strtotime($dateFrom),
                strtotime($dateTo),
            ])->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getCreditCardsInProgressForCreditCardReceiptReport($dateFrom, $dateTo)
    {
        return ApprFDPayment::select(\DB::raw(
            "COUNT(appr_order.id) as total_orders, 
            SUM(amount) as total_amount"
        ))->leftJoin('appr_order', 'appr_order.id', '=', 'appr_fd_payments.order_id')
            ->where('is_success', 1)
            ->where('ref_type', self::CHARGE)
            ->whereNotIn('status', [
                Order::STATUS_APPRAISAL_COMPLETED,
                Order::STATUS_TEMP,
                Order::STATUS_CANCELLED,
                Order::STATUS_AWAITING_CLIENT_APPROVAL
            ])->whereBetween('created_date', [
                strtotime($dateFrom),
                strtotime($dateTo)
            ])->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getChecksCompletedForCreditCardReceiptReport($dateFrom, $dateTo)
    {
        return ApprFDPayment::select(\DB::raw(
            "COUNT(appr_order.id) as total_orders, 
            SUM(amount) as total_amount"
        ))->leftJoin('appr_order', 'appr_order.id', '=', 'appr_fd_payments.order_id')
            ->where('status', Order::STATUS_APPRAISAL_COMPLETED)
            ->whereBetween('created_date', [
                strtotime($dateFrom),
                strtotime($dateTo)
            ])->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getChecksInProgressForCreditCardReceiptReport($dateFrom, $dateTo)
    {
        return ApprFDPayment::select(\DB::raw(
            "COUNT(appr_order.id) as total_orders, 
            SUM(amount) as total_amount"
        ))->leftJoin('appr_order', 'appr_order.id', '=', 'appr_fd_payments.order_id')
            ->whereNotIn('status', [
                Order::STATUS_APPRAISAL_COMPLETED,
                Order::STATUS_TEMP,
                Order::STATUS_CANCELLED,
                Order::STATUS_AWAITING_CLIENT_APPROVAL
            ])->whereBetween('created_date', [
                strtotime($dateFrom),
                strtotime($dateTo)
            ])->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @return Collection|\Illuminate\Support\Collection|static[]
     */
    public function getCreditCardsCompletedOneForCreditCardReceiptReport($dateFrom, $dateTo)
    {
        return ApprFDPayment::select(\DB::raw(
            "COUNT(appr_order.id) as total_orders, 
            SUM(amount) as total_amount, 
            COUNT(appr_fd_payments.id) as total_payments"
        ))->leftJoin('appr_order', 'appr_order.id', '=', 'appr_fd_payments.order_id')
            ->where('is_success', 1)
            ->where('ref_type', self::CHARGE)
            ->where('status', Order::STATUS_APPRAISAL_COMPLETED)
            ->whereBetween('created_date', [
                strtotime($dateFrom),
                strtotime($dateTo)
            ])->groupBy('order_id')->having('total_payments', 1)->get();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getCreditCardsCompletedMoreThanOneForCreditCardReceiptReport($dateFrom, $dateTo)
    {
        return ApprFDPayment::select(\DB::raw(
            "COUNT(appr_order.id) as total_orders, 
            SUM(amount) as total_amount, 
            COUNT(appr_fd_payments.id) as total_payments"
        ))->leftJoin('appr_order', 'appr_order.id', '=', 'appr_fd_payments.order_id')
            ->where('is_success', 1)
            ->where('ref_type', self::CHARGE)
            ->where('status', Order::STATUS_APPRAISAL_COMPLETED)
            ->whereBetween('created_date', [
                strtotime($dateFrom),
                strtotime($dateTo)
            ])
            ->groupBy('order_id')->having('total_payments', '>', 1)->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getCreditCardsForRefundReport($dateFrom, $dateTo)
    {
        return ApprFDPayment::select(\DB::raw(
            "COUNT(appr_order.id) as total_orders, 
            SUM(amount) as total_amount"
        ))->leftJoin('appr_order', 'appr_order.id', '=', 'appr_fd_payments.order_id')
            ->where('is_success', 1)
            ->where('ref_type', self::REFUND)
            ->whereBetween('created_date', [
                strtotime($dateFrom),
                strtotime($dateTo)
            ])->first();
    }

    /**
     * @param $data
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getCreditCardsInProgressYesterday($data)
    {
        return ApprFDPayment::select(\DB::raw(
            "COUNT(appr_order.id) as total_orders, 
            SUM(amount) as total_amount"
        ))->leftJoin('appr_order', 'appr_order.id', '=', 'appr_fd_payments.order_id')
            ->where('is_success', 1)
            ->where('ref_type', self::CHARGE)
            ->whereNotIn('status', [
                Order::STATUS_TEMP,
                Order::STATUS_CANCELLED
            ])->whereBetween('created_date', [
                strtotime($data['dateFrom']),
                strtotime($data['dateTo'])
            ])->where(function ($query) use ($data) {
                return $query->getDateCondition($data['dateFrom'], $data['dateTo'], $data['dateType'])
                    ->orWhereNull('date_delivered');
            })->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getCreditCardsInProgress($dateFrom, $dateTo)
    {
        return ApprFDPayment::select(\DB::raw(
            "COUNT(appr_order.id) as total_orders, 
            SUM(amount) as total_amount"
        ))->leftJoin('appr_order', 'appr_order.id', '=', 'appr_fd_payments.order_id')
            ->where('is_success', 1)
            ->where('ref_type', self::CHARGE)
            ->whereNotIn('status', [
                Order::STATUS_APPRAISAL_COMPLETED,
                Order::STATUS_TEMP,
                Order::STATUS_CANCELLED,
                Order::STATUS_AWAITING_CLIENT_APPROVAL
            ])->whereNull('date_delivered')
            ->whereBetween('created_date', [
                strtotime($dateFrom),
                strtotime($dateTo)
            ])->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getDeliveriesTodayPaidToday($dateFrom, $dateTo, $dateType)
    {
        return ApprFDPayment::select(\DB::raw(
            "COUNT(appr_order.id) as total_orders, 
            SUM(amount) as total_amount"
        ))->leftJoin('appr_order', 'appr_order.id', '=', 'appr_fd_payments.order_id')
            ->where('is_success', 1)
            ->where('ref_type', self::CHARGE)
            ->whereNotIn('status', [
                Order::STATUS_TEMP,
                Order::STATUS_CANCELLED
            ])->whereBetween('created_date', [
                strtotime($dateFrom),
                strtotime($dateTo)
            ])->getDateCondition($dateFrom, $dateTo, $dateType)->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getDeliveriesTodayPaidPast($dateFrom, $dateTo, $dateType)
    {
        return ApprFDPayment::select(\DB::raw(
            "COUNT(appr_order.id) as total_orders, 
            SUM(amount) as total_amount"
        ))->leftJoin('appr_order', 'appr_order.id', '=', 'appr_fd_payments.order_id')
            ->where('is_success', 1)
            ->where('ref_type', self::CHARGE)
            ->whereNotIn('status', [
                Order::STATUS_TEMP,
                Order::STATUS_CANCELLED
            ])->where('created_date', '<=', strtotime(
                date('Y-m-d 00:00:00', strtotime('-1 day', strtotime($dateFrom))))
            )->getDateCondition($dateFrom, $dateTo, $dateType)->first();
    }

    /**
     * @param $data
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getCreditCardsInProgressCurrent($data)
    {
        return ApprFDPayment::select(\DB::raw(
            "COUNT(appr_order.id) as total_orders, 
            SUM(amount) as total_amount"
        ))->leftJoin('appr_order', 'appr_order.id', '=', 'appr_fd_payments.order_id')
            ->where('is_success', 1)
            ->where('ref_type', self::CHARGE)
            ->whereNotIn('status', [
                Order::STATUS_TEMP,
                Order::STATUS_CANCELLED
            ])->whereNull('date_delivered')
            ->whereBetween('created_date', [
                strtotime($data['dateFrom']),
                strtotime($data['dateTo']),
            ])->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @return Builder
     */
    public function getCreditCardsForPaymentsCollectedReport($dateFrom, $dateTo, $dateType)
    {
        return ApprFDPayment::from('appr_fd_payments as fd')
            ->select(\DB::raw(
                "a.id, a.propaddress1 as address, a.propcity as city, 
                UPPER(a.propstate) as state, a.propzip as zip, 
                t.descrip as  team_title,
                FROM_UNIXTIME(fd.created_date, '%m/%d/%Y %H:%i') 
                as payment_received_date, fd.amount, 
                'credit-card' as 'payment_type', fd.ref_type"
            ))->leftJoin('appr_order as a', 'fd.order_id', 'a.id')
            ->leftJoin('admin_team_client as tc', 'a.groupid', 'tc.user_group_id')
            ->leftJoin('admin_teams as t', 't.id', 'tc.team_id')
            ->where('fd.is_success', 1)
            ->where('fd.ref_type', self::CHARGE)
            ->getDateCondition($dateFrom, $dateTo, $dateType, 'fd', 'a')
            ->orderBy('fd.created_date');
    }
}
