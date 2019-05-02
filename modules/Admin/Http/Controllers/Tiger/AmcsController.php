<?php
    
namespace Modules\Admin\Http\Controllers\Tiger;

use App\Models\Tiger\Amc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\Tiger\AmcsRequest;
use Yajra\DataTables\Facades\Datatables;

class AmcsController extends AdminBaseController
{
    
    /**
     * Index page for Surveys
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::tiger.amcs.index');
    }
    
    /**
     * Gather data for index page and datatables
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            
            $amcs = Amc::all();
            
            return Datatables::of($amcs)
                ->editColumn('is_active', function ($r) {
                    return ($r->is_active) ? 'Yes' : 'No';
                })
                ->editColumn('card_on_file', function ($r) {
                    return 'N/A';
                })
                ->addColumn('action', function ($r) {
                    return view('admin::tiger.amcs.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }
    
    /**
     * Create new property type
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin::tiger.amcs.create');
    }
    
    /**
     * Store new property type
     *
     * @param AmcsRequest $request
     * @param Amc $amc
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AmcsRequest $request, Amc $amc)
    {
        $amc->store($request);
        Session::flash('success', 'AMC Successfully Created.');
        
        return redirect()->route('admin.tiger.amcs.index');
    }
    
    /**
     * Edit property type details
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $amc = Amc::findOrFail($id);
        $associations = array_pluck($amc->associations()->get()->toArray(), 'email');
        $associations = implode("\n", $associations);
        
        return view('admin::tiger.amcs.edit', compact('amc', 'associations'));
    }
    
    /**
     * Update Property type details
     *
     * @param AmcsRequest $request
     * @param Amc $amc
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AmcsRequest $request, Amc $amc)
    {
        $amc->store($request);
        Session::flash('success', 'Amc Successfully Updated.');
        
        return redirect()->route('admin.tiger.amcs.index');
    }
    
}
