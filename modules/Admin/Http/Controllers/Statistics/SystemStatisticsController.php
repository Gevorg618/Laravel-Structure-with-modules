<?php

namespace Modules\Admin\Http\Controllers\Statistics;

use Modules\Admin\Http\Controllers\AdminBaseController;

class SystemStatisticsController extends AdminBaseController {

    /**
    * Index
    * @return view
    */
    public function index()
    {
        $tickets = getSystemStatsTickets();
        $users = getSystemStatsTotalUsers();
        $orders = getSystemStatsTotalOrders();
        $orderLogs = getSystemStatsTotalOrderLogs();
        $orderFiles = getSystemStatsTotalOrderFiles();
        $otherFiles = getSystemStatsTotalOtherFiles();
        $fileSizes = getSystemStatsTotalFileSizes();
        $dbInfo = getSystemStatsDBInfo();

        return view('admin::statistics.system-statistics.index',
                compact(
                        'tickets',
                        'users',
                        'orders',
                        'orderLogs',
                        'orderFiles',
                        'otherFiles',
                        'fileSizes',
                        'dbInfo'
                    )
            );
    }

}
