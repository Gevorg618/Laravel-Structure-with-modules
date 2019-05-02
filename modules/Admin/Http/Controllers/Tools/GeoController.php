<?php 

namespace Modules\Admin\Http\Controllers\Tools;

use Modules\Admin\Http\Controllers\AdminBaseController;

use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Modules\Admin\Repositories\Tools\GeoRepository;
use Modules\Admin\Http\Requests\Tools\GeoRequest;
use Session;

class GeoController extends AdminBaseController 
{
    /**
     * Object of GeoRepository class
     *
     * @var geoRepo
     */
    private $geoRepo;
    
    /**
     * Create a new instance of GeoController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->geoRepo = new GeoRepository();
    }
    
    /**
     * GET /admin/tools/geo/index
     *
     * Geo Manager index page
     *
     * @return view
     */
    public function index()
    {
        return view('admin::tools.geo-manager.index');
    }

    /**
     * GET /admin/tools/geo/data
     *
     * method get data for showing with ajax datatable 
     *
     * @param Request $request
     *
     * @return array $customPages
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {

            $states = $this->geoRepo->statesDataTables();

            return $states;
        }
    }

    /**
     * GET /admin/tools/geo/create
     *
     * Create GEO page
     *
     * @return view
     */
    public function create()
    {        
        $regions = $this->geoRepo->regions()->pluck('name', 'id')->prepend('-- Select Region --', '');
        $timezones = $this->geoRepo->timezones()->pluck('name', 'id')->prepend('-- Select Timezone --', '');
        $states = $this->geoRepo->states()->pluck('state', 'id');
        
        return view('admin::tools.geo-manager.create', compact('regions', 'timezones', 'states'));
    }

    /** 
    * POST /admin/tools/geo/create
    *
    * create geo
    *
    * @param GeoRequest $request
    * 
    * @return view
    */
    public function store(GeoRequest $request)
    {

        $state = $this->geoRepo->createState($request);

        if ($state['success']) {

            Session::flash('success', $state['message']);

            return redirect()->route('admin.tools.geo.index');
        
        } else {

            Session::flash('error', $state['message']);

            return redirect()->route('admin.tools.geo.index');
        }

    }

    /**
    * GET /admin/tools/geo/edit
    *
    * Edit GEO page
    *
    * @param integer $id
    *
    * @return view
    */
    public function edit($id)
    {
        $state = $this->geoRepo->state($id);
        
        if ($state) {

            $regions = $this->geoRepo->regions()->pluck('name', 'id')->prepend('-- Select Region --', '');
            $timezones = $this->geoRepo->timezones()->pluck('name', 'id')->prepend('-- Select Timezone --', '');
            $states = $this->geoRepo->states()->pluck('state', 'id');

            return view('admin::tools.geo-manager.edit', compact('state', 'regions', 'timezones', 'states'));

        } else {
            
            return redirect()->route('admin.tools.geo.index');
        }
    }

    /**
    * PUT /admin/tools/geo/update
    *
    * Update GEO
    *
    * @param integer $id
    * @param GeoRequest $request
    *
    * @return view
    */
    public function update($id, GeoRequest $request)
    {
        $isUpdated = $this->geoRepo->update($id, $request);

        if ($isUpdated['success']) {

            Session::flash('success', $isUpdated['message']);

            return redirect()->route('admin.tools.geo.index');
        
        } else {

            Session::flash('error', $isUpdated['message']);

            return redirect()->route('admin.tools.geo.index');
        }
    }

    /**
    * GET /admin/tools/geo/delete
    *
    * Delete GEO 
    *
    * @param integer $id
    *
    * @return view
    */
    public function delete($id)
    {
        $isDeleted = $this->geoRepo->delete($id);

        if ($isDeleted) {

            Session::flash('success', 'GEO is deleted.');
            
            return redirect()->route('admin.tools.geo.index');

        } else {

            Session::flash('error', 'GEO is not found.');
            
            return redirect()->route('admin.tools.geo.index');
        }
    }

}