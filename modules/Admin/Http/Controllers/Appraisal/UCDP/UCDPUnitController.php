<?php

namespace Modules\Admin\Http\Controllers\Appraisal\UCDP;

use App\Helpers\Widget;
use App\Models\Customizations\LoanType;
use App\Models\Customizations\Type;
use App\Models\Appraisal\UCDP\UCDPUnit;
use App\Models\Appraisal\UCDP\UCDPUnitFnmSSN;
use App\Models\Appraisal\UCDP\UCDPUnitFreSSN;
use App\Models\Clients\Client;
use App\Models\Management\WholesaleLenders\UserGroupLender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\Appraisal\UCDPUnitRequest;
use Modules\Admin\Http\Requests\Appraisal\UCDPUnitSSNRequest;
use Yajra\DataTables\Datatables;

class UCDPUnitController extends AdminBaseController
{
    public function index()
    {
        return view('admin::appraisal.ucdp.index');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function ucdpData(Request $request)
    {
        if ($request->ajax()) {
            $ucdpData = UCDPUnit::select(['*']);
            return Datatables::of($ucdpData)
                ->editColumn('is_active', function ($r) {
                    return $r->is_active?'Yes':'No';
                })
                ->editColumn('fnm_active', function ($r) {
                    return $r->fnm_active?'Yes':'No';
                })
                ->editColumn('fre_active', function ($r) {
                    return $r->fre_active?'Yes':'No';
                })
                ->addColumn('action', function ($r) {
                    return view('admin::appraisal.ucdp.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }

    /**
     * @param UCDPUnitRequest $request
     * @param UCDPUnit $ucdpUnit
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function createUCDPUnit(UCDPUnitRequest $request,UCDPUnit $ucdpUnit) {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $create = $ucdpUnit->create($data);
            if ($create) {
                $ucdpUnit->saveRelations($create,$data);
                Session::flash('success', 'Business Unit Created.');
                return redirect()->route('admin.appraisal.ucdp-unit');
            }
        }
        $ucdpUnit->appr_type_all = multiselect(Type::all(), ['form', 'short_descrip']);
        $ucdpUnit->loan_type_all = multiselect(LoanType::all(), 'descrip');
        $ucdpUnit->clients_all = multiselect(Client::select(['id', 'descrip'])->get(), 'descrip');
        $ucdpUnit->lenders_all = multiselect(UserGroupLender::all(), 'lender');
        return view('admin::appraisal.ucdp.create', compact('ucdpUnit'));
    }

    /**
     * @param UCDPUnitRequest $request
     * @param UCDPUnit $ucdpUnit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUCDPUnit(
        UCDPUnitRequest $request,
        UCDPUnit $ucdpUnit
    ) {
        if ($request->isMethod('put')) {
            $data = $request->all();
            $update = $ucdpUnit->update($data);
            $ucdpUnit->updateRelations($ucdpUnit,$data);
            Session::flash('success', 'Business Unit Updated.');
            return redirect()->route('admin.appraisal.ucdp-unit');
        }
        return redirect()->route('admin.appraisal.ucdp-unit');
    }

    /**
     * @param UCDPUnit $ucdpUnit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteUCDPUnit(UCDPUnit $ucdpUnit)
    {
        $ucdpUnit->delete();
        Session::flash('success', 'Business Unit Deleted.');
        return redirect()->route('admin.appraisal.ucdp-unit');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFNM(Request $request)
    {
        if($request->ajax()){
            $fnmSSN = UCDPUnitFnmSSN::find($request->get('id'));
            if($fnmSSN->delete()){
                return response()->json(['success' => true]);
            }
        }
        return response()->json(['error' => false]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFRE(Request $request)
    {
        if($request->ajax()){
            $freSSN = UCDPUnitFreSSN::find($request->get('id'));
            if($freSSN->delete()){
                return response()->json(['success' => true]);
            }
        }
        return response()->json(['error' => false]);
    }

    /**
     * @param UCDPUnitSSNRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editFnm(UCDPUnitSSNRequest $request)
    {
        if($request->ajax()){
            $data[$request->get('name')] = $request->get('value');
            $fnm = UCDPUnitFnmSSN::where('id',$request->get('pk'))->update($data);
            if($fnm){
                return response()->json(['success' => true]);
            }
        }
    }

    /**
     * @param UCDPUnitSSNRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editFre(UCDPUnitSSNRequest $request)
    {
        if($request->ajax()){
            $data[$request->get('name')] = $request->get('value');
            $fnm = UCDPUnitFreSSN::where('id',$request->get('pk'))->update($data);
            if($fnm){
                return response()->json(['success' => true]);
            }
        }
    }
}
