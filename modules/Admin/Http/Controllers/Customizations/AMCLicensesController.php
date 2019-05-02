<?php
namespace Modules\Admin\Http\Controllers\Customizations;

use Illuminate\Http\Request;
use App\Http\Requests;
use Modules\Admin\Http\Controllers\AdminBaseController;
use App\Models\Customizations\AMCLicense;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Requests\Customizations\AMCLicensesRequest;

class AMCLicensesController extends AdminBaseController
{
    public function index()
    {
        return view('admin::customizations.amc-licenses.index');
    }

    /**
     * Process datatables ajax request
     * @param Request $request
     * @return mixed
     */
    public function amcLicensesData(Request $request)
    {
        if ($request->ajax()) {
            $AMCLicenses = AMCLicense::all();
            return Datatables::of($AMCLicenses)
                ->editColumn('state', function ($r) {
                    return getStateByAbbr($r->state);
                })
                ->addColumn('action', function ($r) {
                    return view('admin::customizations.amc-licenses.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }

    /**
     * @param AMCLicensesRequest $request
     * @param AMCLicense $AMCLicense
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function createAMCLicense(AMCLicensesRequest $request, AMCLicense $AMCLicense)
    {
        if($request->isMethod('post')) {
            $data = $request->all();
            $create = $AMCLicense->create($data);
            if ($create) {
                Session::flash('success', 'AMC Registration Created.');
                return redirect()->route('admin.management.amc-licenses');
            }
        }
        $states = getStates();
        $states = array_prepend($states,'--','');

        return view('admin::customizations.amc-licenses.create', compact('AMCLicense','states'));
    }

    /**
     * @param AMCLicensesRequest $request
     * @param AMCLicense $AMCLicense
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAMCLicense(AMCLicensesRequest $request, AMCLicense $AMCLicense)
    {
        if ($request->isMethod('put')) {
            $data = $request->all();

            $update = $AMCLicense->update($data);
            if ($update) {
                Session::flash('success', 'AMC Registration Updated.');
            }
        }
        return redirect()->route('admin.management.amc-licenses');
    }

    /**
     * @param AMCLicense $AMCLicense
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function deleteAMCLicense(AMCLicense $AMCLicense)
    {
        $AMCLicense->delete();
        Session::flash('success', 'AMC Registration Deleted.');
        return redirect()->route('admin.management.amc-licenses');
    }
}