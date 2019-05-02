<?php

namespace Modules\Admin\Http\Controllers\Statistics;

use Modules\Admin\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Repositories\Statistics\DashboardStatisticsRepository;

class DashboardStatisticsController extends AdminBaseController
{   

    /**
     * Object of DashboardStatisticsRepository class
     *
     * @var dashboardStatisticsRepo
     */
    private $dashboardStatisticsRepo;
    
    /**
     * Create a new instance of DashboardStatisticsController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->dashboardStatisticsRepo = new DashboardStatisticsRepository();
    }

    /**
     * 
     * @return View
     */
    public function index()
    {   

        return view('admin::statistics.dashboard.index');
    }

    /**
     * show 
     *
     * @param Request $request
     * 
     * @return response
     */
    public function show(Request $request)
    {   
        
        if ($request->ajax()) {

            //get dashboard statistiscs more info 
            $data = $this->dashboardStatisticsRepo->statistics(
                date('Y-m-d', strtotime($request->get('date_from'))),
                date('Y-m-d', strtotime($request->get('date_to'))),
                $request->get('request_type'),
                $request->get('start'),
                $request->get('length')
            );
            return $data;
        } else {

            return redirect()->back();
        }
    }

}
