<?php 

namespace Modules\Admin\Http\Controllers\AutoSelectPricing;

use Modules\Admin\Http\Controllers\AdminBaseController;
use App\Models\Tools\{Setting, SettingCategory};
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\AutoSelectPricing\AutoSelectCounties;
use App\Models\Users\UserData;
use Illuminate\Support\Collection;
use Carbon\Carbon, Session, Input, Html, DB, Validator, Auth, Response, Exception;

class AutoSelectCountiesController extends AdminBaseController {
  
    /**
    * Index
    * @param $request
    * @return index page
    */
    public function index()
    {
        return view('admin::auto_select_pricing.auto_select_counties.index');
    }

    /**
    * Edit
    * @param $id
    * @return update form
    */
    public function edit($slug)
    {
        $states = getStates();
        $stateName = $states[$slug];
        $stateAllCounties = DB::table('zip_code')->select('county')->where('state', $slug)->distinct()->get();
        $stateSelectedCounties = AutoSelectCounties::where('state', $slug)->get()->toArray();
        return view('admin::auto_select_pricing.auto_select_counties.edit', compact('stateAllCounties', 'stateSelectedCounties', 'stateName', 'slug'));
    }

    /**
    * Update
    * @param $request, $id
    * @return void
    */
    public function update($slug, Request $request)
    {
        $inputs = $request->all();
        AutoSelectCounties::where('state', $slug)->delete();

        foreach ($inputs as $key => $value) {
            if (substr($key, 0, 2) == $slug) {
                AutoSelectCounties::insert([
                    'state' => $slug,
                    'county' => $value
                ]);
            }
        }
        Session::flash('success', 'Successfully Updated!');
        return redirect(route('admin.autoselect.counties'));
    }


   /**
     * Yajra\Datatables\Datatables api route for front-end
     *
     * @return \Response
     */
    public function data()
    {
        $counties = AutoSelectCounties::all();
        $states = getStates();
        $allStates = new Collection;
        $i = 0;
        foreach ($states as $key => $value) {
            $stateAllCounties = DB::table('zip_code')->select('county')->where('state', $key)->distinct()->get();
            $stateSelectedCounties = AutoSelectCounties::where('state', $key)->get();
        	$allStates->push([
                'key' => $key,
                'state'  => $value,
                'total_counties' => $stateAllCounties->count(),
                'selected_counties' => $stateSelectedCounties->count(),
            ]);
        }

        return Datatables::of($allStates)
            ->addColumn('total_counties', function ($r) {
                return view('admin::auto_select_pricing.auto_select_counties.partials._total_counties', ['row' => $r]);
            })
            ->addColumn('selected_counties', function ($r) {
                return view('admin::auto_select_pricing.auto_select_counties.partials._selected_counties', ['row' => $r]);
            })
            ->addColumn('action', function ($r) {
                return view('admin::auto_select_pricing.auto_select_counties.partials._options', ['row' => $r]);
            })
            ->make(true);
    }
}