<?php

namespace Modules\Admin\Http\Controllers\Management;

use Carbon;
use Datatables;
use App\Helpers\Address;
use Illuminate\Http\Request;
use App\Models\Management\ASCLicense;
use Modules\Admin\Http\Controllers\AdminBaseController;

class ASCLicensesController extends AdminBaseController
{
    public function index()
    {
        return view('admin::management.asc-licenses.index');
    }

    /**
     * Processing datatables ajax
     * @param Request $request
     * @return mixed
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            $collection = ASCLicense::query();

            return DataTables::of($collection)
                ->editColumn('status', function ($r) {
                    return $r->statusTitle;
                })
                ->editColumn('address', function ($r) {
                    return Address::getFullAddress($r->street, '', $r->city, $r->state, $r->zip);
                })
                ->editColumn('lic_type', function ($r) {
                    return $r->licenseType;
                })
                ->editColumn('exp_date', function ($r) {
                    return formatDate($r->exp_date);
                })
                ->make(true);
        }
    }
}