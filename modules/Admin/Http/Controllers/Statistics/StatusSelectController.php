<?php

namespace Modules\Admin\Http\Controllers\Statistics;

use Modules\Admin\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\Clients\ClientRepository;
use Modules\Admin\Repositories\Statistics\StatusSelectRepository;

class StatusSelectController extends AdminBaseController
{   

    /**
     * Object of StatusSelectRepository class
     *
     * @var statusSelectRepo
     */
    private $statusSelectRepo;
    
    /**
     * Create a new instance of StatusSelectController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->statusSelectRepo = new StatusSelectRepository();
    }

    /**
     * 
     * @return View
     */
    public function index()
    {   
        $leftToRevisitCount = $this->statusSelectRepo->leftToRevisit();
        $toWorkOnCount = $this->statusSelectRepo->toWorkOn();
        $futureRevisitCount = $this->statusSelectRepo->futureRevisit();
        $todayRevisitCount = $this->statusSelectRepo->todayRevisit();
        $multipleRevisitCount = $this->statusSelectRepo->multipleRevisit();

        return view('admin::statistics.status-select.index', compact('leftToRevisitCount', 'toWorkOnCount', 'futureRevisitCount',
         'todayRevisitCount', 'multipleRevisitCount'));
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
            $datatable = $this->statusSelectRepo->statisticsDatatble($request->get('start'), $request->get('length'));
            
            return $datatable;
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
    public function details($slug)
    {
        $data = $this->statusSelectRepo->getDetails($slug);
        $response = view('admin::statistics.status-select.partials._details', $data)->render();
        return response()->json($response);
    }
    
}
