<?php

namespace Modules\Admin\Repositories\Appraisal;

use App\Models\Appraisal\ApprDashboardDelayOrder;

class ApprDashboardDelayOrderRepository
{
    private $dashboardDailyOrder;

    /**
     * ApprDashboardDelayOrderRepository constructor.
     */
    public function __construct()
    {
        $this->dashboardDailyOrder = new ApprDashboardDelayOrder();
    }

    /**
     * Count number of delays added today that were set for tomorrow or later
     */
    public  function futureRevisit()
    {
        return \DB::selectOne("SELECT COUNT(id) as total FROM appr_dashboard_delay_order WHERE created_date BETWEEN :from AND :to AND delay_date > :today", [':from' => strtotime('today'), ':to' => strtotime('tomorrow'), ':today' => strtotime('tomorrow')])->total;
    }

    /**
     * Count number of delays added today that were set for later today
     */
    public function getTotalRevisitToday()
    {
        return \DB::selectOne("SELECT COUNT(id) as total FROM appr_dashboard_delay_order WHERE created_date BETWEEN :from AND :to AND delay_date BETWEEN :t AND :b", [':from' => strtotime('today'), ':to' => strtotime('tomorrow'), ':t' => strtotime('today'), ':b' => strtotime('tomorrow')])->total;
       
    }

    /**
     * Count number of orders that a delay was set for today more than once
     */
    public function getTotalMultipleRevisits()
    {
        $row = \DB::select("SELECT orderid FROM appr_dashboard_delay_order WHERE created_date BETWEEN :from AND :to AND delay_date BETWEEN :t AND :b GROUP BY orderid HAVING COUNT(id) > 1", [':from' => strtotime('today'), ':to' => strtotime('tomorrow'), ':t' => strtotime('today'), ':b' => strtotime('tomorrow')]);
        return count($row);
    }

    /**
     * Get actual orders that were set for future revisit
     */
    public  function getFutureRevisitOrders()
    {
        return \DB::select("SELECT a.id, a.propaddress1, a.propaddress2, a.propcity, a.propstate, a.propzip, s.descrip as status_name FROM appr_dashboard_delay_order d LEFT JOIN appr_order a ON (a.id=d.orderid) LEFT JOIN order_status s ON (a.status=s.id) WHERE d.created_date BETWEEN :from AND :to AND d.delay_date > :today", [':from' => strtotime('today'), ':to' => strtotime('tomorrow'), ':today' => strtotime('tomorrow')]);
    }

    /**
     * Count number of delays added today that were set for later today
     */
    public  function getTodayRevisitOrders()
    {
        return \DB::select("SELECT a.id, a.propaddress1, a.propaddress2, a.propcity, a.propstate, a.propzip, s.descrip as status_name FROM appr_dashboard_delay_order d LEFT JOIN appr_order a ON (a.id=d.orderid) LEFT JOIN order_status s ON (a.status=s.id) WHERE d.created_date BETWEEN :from AND :to AND d.delay_date BETWEEN :t AND :b", [':from' => strtotime('today'), ':to' => strtotime('tomorrow'), ':t' => strtotime('today'), ':b' => strtotime('tomorrow')]);
    }

    /**
     * Count number of orders that a delay was set for today more than once
     */
    public function getMultipleRevisitOrders()
    {
        return \DB::select("SELECT a.id, a.propaddress1, a.propaddress2, a.propcity, a.propstate, a.propzip, s.descrip as status_name FROM appr_dashboard_delay_order d LEFT JOIN appr_order a ON (a.id=d.orderid) LEFT JOIN order_status s ON (a.status=s.id) WHERE d.created_date BETWEEN :from AND :to AND d.delay_date BETWEEN :t AND :b GROUP BY d.orderid HAVING COUNT(d.id) > 1", [':from' => strtotime('today'), ':to' => strtotime('tomorrow'), ':t' => strtotime('today'), ':b' => strtotime('tomorrow')]);
    }
}