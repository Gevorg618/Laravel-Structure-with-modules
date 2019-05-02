<?php

namespace Modules\Admin\Http\Controllers\Customizations;

use Illuminate\Http\Request;
use App\Http\Requests;
use Modules\Admin\Http\Controllers\AdminBaseController;
use App\Models\Customizations\DelayCode;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Requests\Customizations\DelayCodesRequest;

class DelayCodesController extends AdminBaseController
{
    public function index()
    {
        return view('admin::customizations.delay-codes.index');
    }

    /**
     * Process datatables ajax request.
     * @param Request $request
     * @return mixed
     */
    public function delayCodesData(Request $request)
    {
        if ($request->ajax()) {
            $status = DelayCode::all();
            return Datatables::of($status)
                ->addColumn('action', function ($r) {
                    return view('admin::customizations.delay-codes.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }

    /**
     * @param DelayCodesRequest $request
     * @param DelayCode $delayCode
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function createDelayCodes(DelayCodesRequest $request,DelayCode $delayCode)
    {
        if($request->isMethod('post')) {
            $data = $request->all();
            $create = $delayCode->create($data);
            if ($create) {
                Session::flash('success', 'Delay Code Created.');
                return redirect()->route('admin.appraisal.delay-codes');
            }
        }
        return view('admin::customizations.delay-codes.create', compact('delayCode'));
    }

    /**
     * @param DelayCodesRequest $request
     * @param DelayCode $delayCode
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateDelayCodes(DelayCodesRequest $request, DelayCode $delayCode)
    {
        if ($request->isMethod('put')) {
            $data = $request->all();
            $update = $delayCode->update($data);
            if ($update) {
                Session::flash('success', 'Delay Code Updated.');
            }
        }
        return redirect()->route('admin.appraisal.delay-codes');
    }

    /**
     * @param DelayCode $delayCode
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function deleteDelayCodes(DelayCode $delayCode)
    {
        $delayCode->delete();
        Session::flash('success', 'Delay Code Deleted.');
        return redirect()->route('admin.appraisal.delay-codes');
    }
}