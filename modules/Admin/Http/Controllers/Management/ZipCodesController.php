<?php
namespace Modules\Admin\Http\Controllers\Management;

use App\Models\Management\ZipCode;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Yajra\DataTables\Datatables;
use Modules\Admin\Http\Requests\Management\ZipCodeRequest;
use Illuminate\Support\Facades\Session;


class ZipCodesController extends AdminBaseController
{
    public function index()
    {
        return view('admin::management.zip_code.index');
    }

    /**
     * Process datatables ajax request
     * @param Request $request
     * @return mixed
     */
    public function zipCodeData(Request $request)
    {
        if ($request->ajax()) {
            $ZipCode = ZipCode::all();
            return Datatables::of($ZipCode)
                ->editColumn('state', function ($r) {
                    return getStateByAbbr($r->state);
                })
                ->addColumn('action', function ($r) {
                    return view('admin::management.zip_code.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }

    /**
     * @param ZipCodeRequest $request
     * @param ZipCode $ZipCode
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function createZipCode(ZipCodeRequest $request, ZipCode $ZipCode)
    {
        if($request->isMethod('post')) {
            $data = $request->all();
            $create = $ZipCode->create($data);
            if ($create) {
                Session::flash('success', 'Zip Code Created.');
                return redirect()->route('admin.management.zipcodes');
            }
        }
        $states = getStates();
        $states = array_prepend($states,'-All-','');

        return view('admin::management.zip_code.create', compact('ZipCode','states'));
    }

    /**
     * @param ZipCodeRequest $request
     * @param ZipCode $ZipCode
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateZipCode(ZipCodeRequest $request,ZipCode $ZipCode)
    {
        if ($request->isMethod('put')) {
            $data = $request->all();

            $update = $ZipCode->update($data);
            if ($update) {
                Session::flash('success', 'Zip Code Updated.');
            }
        }
        return redirect()->route('admin.management.zipcodes');
    }

    /**
     * @param ZipCode $ZipCode
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function deleteZipCode(ZipCode $ZipCode)
    {
        $ZipCode->delete();
        Session::flash('success', 'Zip Code Deleted.');
        return redirect()->route('admin.management.zipcodes');
    }

}