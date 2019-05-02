<?php

namespace Modules\Admin\Repositories\Accounting;


use App\Models\Accounting\CimCheckPayment;
use App\Models\Appraisal\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CimCheckPaymentRepository
{
    const REFUND = 'REFUND';
    /**
     * @param $checkNumber
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getExportCheckData($checkNumber)
    {
        return CimCheckPayment::from('appr_cim_check_payments as c')
            ->select(\DB::raw("
            a.id, 
            c.check_number, 
            c.amount, 
            c.check_from, 
            FROM_UNIXTIME( c.date_received, '%m/%d/%Y') as date_received, 
            a.borrower,
            a.propaddress1, 
            a.propcity, 
            a.propstate, 
            a.propzip, 
            a.loanrefnum, 
            a.ordereddate, 
            a.date_delivered
        "))->leftJoin(
                'appr_order as a',
                'c.order_id',
                '=',
                'a.id'
            )->where('c.check_number', $checkNumber)->get();
    }

    /**
     * @param $from
     * @param $to
     * @param $type
     * @return Builder
     */
    public function getDailyBatchData($from, $to, $type)
    {
        return CimCheckPayment::with([
            'order'
        ])->whereBetween('created_date', [
            strtotime($from . ' 00:00:00'),
            strtotime($to . ' 23:59:59')
        ])->dailyBatchFilter($type)
            ->orderBy('created_date');
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @return Collection|\Illuminate\Support\Collection|static[]
     */
    public function getChecksCompletedMoreThanOneForCreditCardReceiptReport($dateFrom, $dateTo)
    {
        return CimCheckPayment::select(\DB::raw(
            "COUNT(appr_order.id) as total_orders, 
            SUM(amount) as total_amount, 
            COUNT(appr_cim_check_payments.id) as total_payments"
        ))->leftJoin('appr_order', 'appr_order.id', '=', 'appr_cim_check_payments.order_id')
            ->where('status', Order::STATUS_APPRAISAL_COMPLETED)
            ->whereBetween('created_date', [
                strtotime($dateFrom),
                strtotime($dateTo)
            ])->groupBy('order_id')
            ->having('total_payments', '>', 1)->get();

    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @return Collection|\Illuminate\Support\Collection|static[]
     */
    public function getChecksCompletedOneForCreditCardReceiptReport($dateFrom, $dateTo)
    {
        return CimCheckPayment::select(\DB::raw(
            "COUNT(appr_order.id) as total_orders, 
            SUM(amount) as total_amount, 
            COUNT(appr_cim_check_payments.id) as total_payments"
        ))->leftJoin('appr_order', 'appr_order.id', '=', 'appr_cim_check_payments.order_id')
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
    public function getChecksForRefundReport($dateFrom, $dateTo)
    {
        return CimCheckPayment::select(\DB::raw(
            "COUNT(appr_order.id) as total_orders, 
            SUM(amount) as total_amount"
        ))->leftJoin('appr_order', 'appr_order.id', '=', 'appr_cim_check_payments.order_id')
            ->where('ref_type', self::REFUND)
            ->whereBetween('created_date', [
                strtotime($dateFrom),
                strtotime($dateTo)
            ])->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @return Builder
     */
    public function getChecksForPaymentsCollectedReport($dateFrom, $dateTo, $dateType)
    {
        return CimCheckPayment::from('appr_cim_check_payments as fd')
            ->select(\DB::raw(
                "a.id, a.propaddress1 as address, a.propcity as city, 
                UPPER(a.propstate) as state, a.propzip as zip, 
                t.descrip as  team_title,
                FROM_UNIXTIME(fd.created_date, '%m/%d/%Y %H:%i') as payment_received_date, 
                fd.amount, 'check' as 'payment_type', fd.ref_type"
            ))->leftJoin('appr_order as a', 'fd.order_id', 'a.id')
            ->leftJoin('admin_team_client as tc', 'a.groupid', 'tc.user_group_id')
            ->leftJoin('admin_teams as t', 't.id', 'tc.team_id')
            ->where('fd.ref_type', '!=', self::REFUND)
            ->getDateCondition($dateFrom, $dateTo, $dateType, 'fd', 'a')
            ->orderBy('fd.created_date');
    }

    public static function getCheckPaymentHistory($orderId) {
        return CimCheckPayment::where('order_id', $orderId)
            ->latest('created_date')->get();
    }
}
