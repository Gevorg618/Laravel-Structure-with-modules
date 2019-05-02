<?php

namespace Modules\Admin\Http\Controllers\Statistics;

use Modules\Admin\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Repositories\Statistics\StatisticsRepository;
use Modules\Admin\Repositories\Clients\ClientRepository;

class StatisticsController extends AdminBaseController
{   

    /**
     * Object of StatisticsRepository class
     *
     * @var statisticsRepo
     */
    private $statisticsRepo;

    /**
     * Object of ClientRepository class
     *
     * @var clientRepo
     */
    public $clientRepo;
    
    /**
     * Create a new instance of StatisticsController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->statisticsRepo = new StatisticsRepository();
        $this->clientRepo = new ClientRepository();
    }

    /**
     * 
     * @return View
     */
    public function index()
    {   
        $clients = $this->clientRepo->clients()->pluck('descrip', 'id');
        $calendar = $this->statisticsRepo->calendar();
        return view('admin::statistics.statistics.index', compact('clients', 'calendar'));
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

            // get statistiscs more info 
            $data = $this->statisticsRepo->statistics(
                date('Y-m-d', strtotime($request->get('date_from'))),
                date('Y-m-d', strtotime($request->get('date_to'))),
                $request->get('clients'), 
                $request->get('request_type'),
                $request->get('start'),
                $request->get('length')
            );
            
            return $data;
        } else {

            return redirect()->back();
        }
    }

    /**
     * show orders statistics by from-to date and  by clients
     *
     * @param Request $request
     * 
     * @return response
     */
    public function calendarData(Request $request)
    {   

        if ($request->ajax()) {

            // get statistiscs more info 
            $data = $this->statisticsRepo->calendarEvents(
                gmdate('Y-m-d H:i:s',$request->get('start_date')),
                gmdate('Y-m-d H:i:s',$request->get('end_date'))
            );
            // return null;
            return $data;
        } else {

            return redirect()->back();
        }
    }

    
}
