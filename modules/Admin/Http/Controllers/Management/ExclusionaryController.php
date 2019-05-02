<?php

namespace Modules\Admin\Http\Controllers\Management;

use App\Helpers\Widget;
use App\Models\Management\WholesaleLenders\UserGroupLender;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Yajra\DataTables\Datatables;

class ExclusionaryController extends AdminBaseController
{
    public function index()
    {
        $excludedProfiles = UserGroupLender::has('profiles')->with(['profiles','profiles.userData'])->count();
        $licenses = UserGroupLender::has('licenses')->with('licenses')->count();
        $count = [
            'profiles' => $excludedProfiles,
            'licenses' => $licenses,
        ];
        $lenders = multiselect(UserGroupLender::get(['id','lender']),'lender');
        $states = getStates();
        return view('admin::management.exclusionary.index', compact('count','lenders','states'));
    }

    /**
     * Processing datatables ajax
     * @param Request $request
     * @return mixed
     */
    public function exclusionaryData(Request $request)
    {
        if ($request->ajax()) {
            $excludedProfiles = UserGroupLender::has('profiles')->with(['profiles','profiles.userData'])->get()
                ->map(function ($item) {
                    return $item->profiles->map(function ($prof) use ($item) {
                        return [
                            'lender_id' => $item->id,
                            'lender' => $item->lender,
                            'appraiser_id' => $prof->id,
                            'appraiser' => $prof->userData->firstname . ' ' . $prof->userData->lastname,
                            'created_date' => Carbon::createFromTimestamp($prof->pivot->created_date)->format('Y-m-d')
                        ];
                    });
                });
            $data = $excludedProfiles->flatten(1);
            if ($request->filled('filter') && !empty($request->get('filter'))) {
                $data = $data->whereIn('lender_id',$request->get('filter'));
            }
            return Datatables::of($data)->make(true);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function licensesData(Request $request)
    {
        if($request->ajax()){
            $licenses = UserGroupLender::has('licenses')->with('licenses')->get()
            ->map(function ($item) {
                return $item->licenses->map(function ($lend) use ($item) {
                    return [
                        'lender_id' => $item->id,
                        'lender' => $item->lender,
                        'appraiser' => $lend->firstname . ' ' . $lend->lastname,
                        'license_state' => $lend->license_state,
                        'license_number' => $lend->license_number
                    ];
                });
            });
            $data = $licenses->flatten(1);
            if ($request->filled('filter') && !empty($request->get('filter'))) {
                $data = $data->whereIn('lender_id',$request->get('filter'));
            }
            if ($request->filled('state') && !empty($request->get('state'))) {
                $data = $data->whereIn('license_state',$request->get('state'));
            }
            return Datatables::of($data)->make(true);
        }
    }


}
