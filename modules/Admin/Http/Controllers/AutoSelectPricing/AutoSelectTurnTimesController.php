<?php

namespace Modules\Admin\Http\Controllers\AutoSelectPricing;

use App\Models\Geo\State;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Carbon\Carbon;
use Modules\Admin\Http\Requests\AutoSelect\AutoSelectTurnTimesRequest;
use Modules\Admin\Repositories\AutoSelectPricing\AutoSelectTurnTimeRepository;
use App\Models\Clients\Client;
use App\Models\Customizations\Type;
use App\Models\AutoSelectPricing\AutoSelectTurnTime;
use App\Models\AutoSelectPricing\AutoSelectClientTurnTime;
use Session;

class AutoSelectTurnTimesController extends AdminBaseController
{

     /**
     * Object of AutoSelectTurnTimeRepository class
     *
     * @var turnTimeRepo
     */
    private $turnTimeRepo;
    
    /**
     * Create a new instance of AutoSelectTurnTimesController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->turnTimeRepo = new AutoSelectTurnTimeRepository();
    }
    
    /**
     * GET /admin/autoselect-pricing/turn-times/index
     *
     * Auto Select Pricing Turn Times index page
     *
     * @return view
     */
    public function index()
    {
        $defaultTurnTime = AutoSelectTurnTime::with(['editedBy'])->first();
        $defaultTurnTimesCount = AutoSelectTurnTime::get()->count();
        $clientTurnTimes = AutoSelectClientTurnTime::groupBy('client_id')->get();

        // get appriasal types 
        $typesCount = Type::count();
        
        return view('admin::auto_select_pricing.turn-times.index', compact('defaultTurnTime', 'typesCount', 'clientTurnTimes', 'defaultTurnTimesCount'));
    }

    /**
     * GET /admin/autoselect-pricing/turn-times/create
     *
     * Create Auto Select Pricing Client Specific Turn Times page
     *
     * @return view
     */
    public function create()
    {        
        // get clients 
        $clients = Client::get()->pluck('descrip', 'id')->prepend('-- Select Client --', '');

        // get appriasal types 
        $types = Type::get(['descrip', 'id', 'form'])->toArray();
        
        return view('admin::auto_select_pricing.turn-times.create', compact('clients', 'types'));
    }

    /** 
    * POST /admin/autoselect-pricing/turn-times/store
    *
    * create auto select  client specific time
    *
    * @return view
    */
    public function store(AutoSelectTurnTimesRequest $request)
    {
        $requestData = $request->except('_token');

        $clientTurnTime = $this->turnTimeRepo->createClientTurnTimes($requestData);

        if ($clientTurnTime['success']) {

            Session::flash('success', $clientTurnTime['message']);

            return redirect()->route('admin.autoselect.turn.times.index');
        
        } else {

            Session::flash('error', $clientTurnTime['message']);

            return redirect()->route('admin.autoselect.turn.times.index');
        }

    }

    /**
    * GET /admin/autoselect-pricing/turn-times/edit
    *
    * Edit Auto slecet turn time page
    *
    * @param integer $id
    *
    * @return view
    */
    public function edit($id)
    {
        
        if ($id == 'default') {
            $turnTimes = $this->turnTimeRepo->defaultTurnTimes();
        } else {
            $turnTimes = $this->turnTimeRepo->clientTurnTimes($id);
        }
        
        
        if ($turnTimes) {
            
            $clients = Client::get()->pluck('descrip', 'id')->prepend('-- Select Client --', '');

            return view('admin::auto_select_pricing.turn-times.edit', compact('turnTimes', 'clients', 'types', 'id'));

        } else {
            
            return redirect()->route('admin.autoselect.turn.times.index');
        }
    }

    /**
    * PUT /admin/autoselect-pricing/turn-times/edit
    *
    * Update Auto Select turn times
    *
    * @param integer $id
    * @param AutoSelectTurnTimesRequest $request
    *
    * @return view
    */
    public function update($id, AutoSelectTurnTimesRequest $request)
    {
        $requestData = $request->except('_token','_method');

        if ($id == 'default') {
             $isUpdated = $this->turnTimeRepo->updateDefaultTurnTimes($requestData);  
        } else {
            $isUpdated = $this->turnTimeRepo->updateClientTurnTimes($id, $requestData);            
        }

        if ($isUpdated['success']) {

            Session::flash('success', $isUpdated['message']);

            return redirect()->route('admin.autoselect.turn.times.index');
        
        } else {

            Session::flash('error', $isUpdated['message']);

            return redirect()->route('admin.autoselect.turn.times.index');
        }
    }

    /**
    * Delete /admin/autoselect-pricing/turn-times/delete
    *
    * Delete auto select client turn time 
    *
    * @param integer $id
    *
    * @return view
    */
    public function destroy($id)
    {
    
        $isDeleted = $this->turnTimeRepo->deleteClientTurnTime($id);

        if ($isDeleted['success']) {

            Session::flash('success', $isDeleted['message']);
            
            return redirect()->route('admin.autoselect.turn.times.index');

        } else {

            Session::flash('error', $isDeleted['message']);
            
            return redirect()->route('admin.autoselect.turn.times.index');
        }
    }
}