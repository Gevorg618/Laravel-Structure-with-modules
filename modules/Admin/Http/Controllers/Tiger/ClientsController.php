<?php
    
namespace Modules\Admin\Http\Controllers\Tiger;

use App\Models\Management\Survey;
use App\Models\Management\UserType;
use App\Models\Tiger\Client;
use App\Models\Tiger\ClientOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\Management\SurveysRequest;
use Modules\Admin\Http\Requests\Tiger\ClientsRequest;
use Yajra\DataTables\Facades\Datatables;

class ClientsController extends AdminBaseController
{
    
    /**
     * Index page for Surveys
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::tiger.clients.index');
    }
    
    /**
     * Gather data for index page and datatables
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            
            $clients = Client::withOption();
            
            return Datatables::of($clients)
                ->editColumn('appr_charge_fee', function ($r) {
                    return ($r->appr_charge_fee) ? 'Yes' : 'No';
                })
                ->editColumn('appr_fee_amount', function ($r) {
                    return isset($r->appr_fee_amount) ? $r->appr_fee_amount : '-';
                })
                ->editColumn('appr_charge_location', function ($r) {
                    return isset($r->appr_charge_location) ? ucfirst($r->appr_charge_location) : '-';
                })
                ->addColumn('card_on_file', function ($r) {
                    return 'N/A';
                })
                ->addColumn('action', function ($r) {
                    return view('admin::tiger.clients.partials._options', ['row' => $r]);
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
        $clientOptions = ClientOption::all();
        return view('admin::tiger.clients.create', compact('clientOptions'));
    }
    
    /**
     * Store new property type
     *
     * @param SurveysRequest $request
     * @param Survey $survey
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ClientsRequest $request, Client $client)
    {
        $client->store($request);
        Session::flash('success', 'Client Successfully Created.');
        
        return redirect()->route('admin.tiger.clients.index');
    }
    
    /**
     * Edit property type details
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        //get all user types
        $clientOptions = ClientOption::all();
        $client = Client::where('id', $id)->with('values')->first();
        $client->options = $client->optionWithValue();

        return view('admin::tiger.clients.edit', compact(['clientOptions','client']));
    }
    
    /**
     * Update Property type details
     *
     * @param SurveysRequest $request
     * @param Survey $survey
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ClientsRequest $request, Client $client)
    {
        $client->store($request);
        Session::flash('success', 'Client Successfully Updated.');
        
        return redirect()->route('admin.tiger.clients.index');
    }
    
}
