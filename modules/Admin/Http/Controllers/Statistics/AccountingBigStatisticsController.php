<?php

namespace Modules\Admin\Http\Controllers\Statistics;

use Modules\Admin\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Repositories\Statistics\AccountingBigStatisticsRepository;

class AccountingBigStatisticsController extends AdminBaseController
{   

    /**
     * Object of AccountingBigStatisticsRepository class
     *
     * @var accountingBigStatisticsRepo
     */
    private $accountingBigStatisticsRepo;
    
    /**
     * Create a new instance of AccountingBigStatisticsController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->accountingBigStatisticsRepo = new AccountingBigStatisticsRepository();
    }

    /**
     * 
     * @return View
     */
    public function index()
    {   
        return view('admin::statistics.accounting-big.index');
    }

    /**
     * show orders statistics by from-to date and  by clients
     *
     * @param Request $request
     * 
     * @return response
     */
    public function show(Request $request)
    {   
        // if ($request->ajax()) {
            
            $data = $this->accountingBigStatisticsRepo->statistics();

            return response()->json($data);
        // } else {

            return redirect()->back();
        // }
    }

}
