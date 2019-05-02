<?php

namespace Modules\Admin\Repositories\Statistics;

use Yajra\DataTables\Datatables;
use Modules\Admin\Repositories\Users\UserRepository;
use Modules\Admin\Repositories\Ticket\OrderRepository;
use Modules\Admin\Repositories\Appraisal\ApprDashboardDailyStatsRepository;
use Modules\Admin\Repositories\Appraisal\ApprDashboardDelayOrderRepository;

class StatusSelectRepository
{

    /**
     * Object of UserRepository class
     *
     * @var userRepo
     */
    private $userRepo;

    /**
     *  Objectof OrderRepository class
     *
     * @var orderRepo
     */
    private $orderRepo;

    /**
     *  Objectof ApprDashboardDailyStatsRepository class
     *
     * @var apprDashboardDailyStatsRepo
     */
    private $apprDashboardDailyStatsRepo;

    /**
     *  Objectof ApprDashboardDelayOrderRepository class
     *
     * @var apprDashboardDelayOrderRepo
     */
    private $apprDashboardDelayOrderRepo;

    /**
     * SalesCommissionReportRepository constructor.
     *
     */
    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->orderRepo = new OrderRepository();
        $this->apprDashboardDailyStatsRepo = new ApprDashboardDailyStatsRepository();
        $this->apprDashboardDelayOrderRepo = new ApprDashboardDelayOrderRepository();
    }

    /**
     * get  statistics more info
     * 
     * @return response 
     */
    public function statisticsDatatble($skip = null, $take = null)
    {
        $orders = $this->orderRepo->statusSelectOrders();
        
        $ordersRawCount = $orders->count();
       
        $orders = $orders->skip((int)$skip)->take((int)$take);

        return  Datatables::of($orders)
                ->editColumn('id', function ($order) {
                    return $order->id;
                })
                ->editColumn('address', function ($order) {
                    return $order->getAddressAttribute();
                })
                ->editColumn('team', function ($order) {
                    return $order->getTeamTitle();
                })
                ->editColumn('transfer', function ($order) {
                    return $order->getClientNameAttribute();
                })
                ->editColumn('status', function ($order) {
                    return $order->getStatusNameAttribute();
                })
                ->editColumn('viewing', function ($order) {
                    return 'N/A';
                })
                ->editColumn('date_time', function ($order) {
                    
                    return $order->apprDashboardDelayOrder()->first() ?  date('m/d/Y g:i A', $order->apprDashboardDelayOrder()->first()->delay_date) .  ($order->apprDashboardDelayOrder()->first()->priority > 1 ? '(High)' : '') : '--';
                })
                ->setTotalRecords($ordersRawCount)
                ->make(true);
    }

    public function leftToRevisit()
    {
        $orders = $this->orderRepo->statusSelectOrders();

        return $orders->count();
    }

    public function toWorkOn()
    {
        $date = date('Y-m-d');
        $total = $this->apprDashboardDailyStatsRepo->needToWorkOn($date)->count();
        
        return $total ?  $total: 0;
    }
    
    public function futureRevisit()
    {
        return $this->apprDashboardDelayOrderRepo->futureRevisit();
    }

    public function todayRevisit()
    {
        return $this->apprDashboardDelayOrderRepo->getTotalRevisitToday();
    }

    public function multipleRevisit()
    {
        return $this->apprDashboardDelayOrderRepo->getTotalMultipleRevisits();
    }

    public function getDetails($slug)
    {
        
        switch ($slug) {
            case 'future-revisit':
                $title = 'Orders Marked For Revisit In The Future';
                $details = $this->apprDashboardDelayOrderRepo->getFutureRevisitOrders();
                break;
            case 'multiple-revisit':
                $title = 'Orders Marked For Revisit Today';
                $details = $this->apprDashboardDelayOrderRepo->getTodayRevisitOrders();
                break;
            case 'today-revisit':
                $title = 'Orders With Multiple Revisits';
                $details = $this->apprDashboardDelayOrderRepo->getMultipleRevisitOrders();
                break;
            default:
                return null;
                break;
        }
        
        return ['details' => $details, 'title' => $title];
    }
}    