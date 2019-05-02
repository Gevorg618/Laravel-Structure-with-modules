<?php

namespace Modules\Admin\Http\Controllers\Statistics;

use Modules\Admin\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\Statistics\SalesCommissionReportRepository;
use Modules\Admin\Repositories\Users\UserRepository;

class SalesCommissionReportsController extends AdminBaseController
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
     * Object of SalesCommissionReportRepository class
     *
     * @var salesCommissionReportRepo
     */
    private $salesCommissionReportRepo;
    
    /**
     * Object of UserRepository class
     *
     * @var userRepo
     */
    private $userRepo;

    /**
     * Create a new instance of DashboardStatisticsController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->salesCommissionReportRepo = new SalesCommissionReportRepository();
        $this->userRepo = new UserRepository();
    }

    /**
     * 
     * @return View
     */
    public function index()
    {   
        $users = $this->userRepo->userPrivilege($this->adminPriv, $this->userActive)->get();

        $userData = [];

        foreach ($users as $user) {
            $userData[$user->id] = $user->getFullNameAttribute();
        }

        return view('admin::statistics.sales-commission.index', compact('userData'));
    }

    /**
     *
     * @param Request $request
     * 
     * @return View
     */
    public function show(Request $request)
    {   
        
        if ($request->ajax()) {

            // get dashboard statistiscs more info 
            $data = $this->salesCommissionReportRepo->statistics(
                date('Y-m-d', strtotime($request->get('date_from'))),
                date('Y-m-d', strtotime($request->get('date_to'))),
                $request->get('date_type'),
                $request->get('user_data'),
                $request->get('start'),
                $request->get('length')
            );
            return $data;
        } else {

            return redirect()->back();
        }
    }

}
