<?php

namespace Modules\Admin\Http\Controllers\Statistics;

use Modules\Admin\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Repositories\Statistics\BigStatisticsRepository;
use Modules\Admin\Http\Requests\Statistics\BigRequest;

class BigStatisticsController extends AdminBaseController
{   

    /**
     * Object of BigStatisticsRepository class
     *
     * @var bigStatisticsRepo
     */
    private $bigStatisticsRepo;
    
    /**
     * Create a new instance of BigStatisticsController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->bigStatisticsRepo = new BigStatisticsRepository();
    }

    /**
     * 
     * @return View
     */
    public function index(BigRequest $request)
    {   
        if ($request->has('date')) {
            $date =  date($request->get('date'));
        } else {
            $date = date('Y-m-d');
        }

        return view('admin::statistics.big.index', compact('date'));
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

        if ($request->ajax()) {

            if ($request->has('date')) {
                $date =  date($request->get('date'));
            } else {
                $date = date('Y-m-d');
            }
            
            // get statistiscs more info 
            $data = $this->bigStatisticsRepo->statistics($date);

            return response()->json($data);
        } else {

            return redirect()->back();
        }
    }

}
