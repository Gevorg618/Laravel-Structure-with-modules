<?php

namespace Modules\Admin\Repositories\Statistics;

use Yajra\DataTables\Datatables;
use Modules\Admin\Repositories\Users\UserRepository;
use Modules\Admin\Repositories\Ticket\OrderRepository;

class SalesCommissionReportRepository
{

    /**
     *
     * @var adminPriv
     */
    private $adminPriv = 'O';

    /**
     *
     * @var userActive
     */
    private $userActive = 'Y';

    /**
     * Object of UserRepository class
     *
     * @var userRepo
     */
    private $userRepo;

    /**
     * Object of OrderRepository class
     *
     * @var orderRepo
     */
    private $orderRepo;

    /**
     * SalesCommissionReportRepository constructor.
     *
     */
    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->orderRepo = new OrderRepository();
    }

    /**
     * get  statistics more info
     *
     * @param date $fromDate
     * @param date $toDate
     * @param string $dateType
     * @param string $userId
     * 
     * @return response 
     */
    public function statistics($fromDate, $toDate, $dateType, $userId = null, $skip = null, $take = null)
    {
            
        $orders = $this->orderRepo->getOrdersByGroups($fromDate, $toDate, $dateType, $userId);

        // dd($orders);
        // // Get user group associated to
        // $groups = $user->first()->groupsBySales;
        // $lenders = $user->first()->lendersBySales;
        // dd($groups);

        $items = [];
        $totals = ['total_client_fee' => 0, 'total_appr_fee' => 0, 'total_margin' => 0, 'total_commission' => 0];
        $alorders = [];

        foreach($orders as $order) {

            // $commission = getApprOrderComissionByOrderId($order->orderid, 'group');

            $items[$order->id] = [
                    'id' => $order->id,
                    'dateordered' => $order->date_delivered,
                    //'orderedby' => removeCommas(getUserFullNameById($order->orderedby, false)),
                    'client' => $order->group_name,
                    'address' => $order->propaddress1,
                    'borrower' => $order->borrower,
                    'status' => $order->descrip,
                    'type' => $order->getApprTypeNameAttribute(),
                    'payment_status' => $order->getPaymentStatusAttribute(),
                    'invoicedue' => $order->invoicedue,
                    'split_amount' => $order->split_amount,
                    'margin' => ($order->invoicedue-$order->split_amount),
                    // 'commission' => $commission,
                    'sale_rep_paid' => $order->sale_rep_paid,
                    'sale_rep_paid_lender' => $order->sale_rep_paid_lender,
                    'order_type' => 'APPR',
                    'commission_type' => 'group',
                ];
            // Add in the totals
            $totals['total_client_fee'] += $order->invoicedue;
            $totals['total_appr_fee'] += $order->split_amount;
            $totals['total_margin'] += ($order->invoicedue-$order->split_amount);
            // $totals['total_comission'] += $commission;
           
        }

        dd($items);


            
    }
}    