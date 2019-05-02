<?php

namespace Modules\Admin\Http\Controllers\Customizations;

use Illuminate\Http\Request;
use App\Http\Requests;
use Modules\Admin\Http\Controllers\AdminBaseController;
use App\Models\Customizations\LoanType;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Session;
use Validator;


class LoanTypeController extends AdminBaseController
{
    public function index()
    {
        return view('admin::customizations.loan-type.index');
    }
    public function loanTypeData(Request $request)
    {
        if ($request->ajax()) {
            $status = LoanType::all();
            return Datatables::of($status)
                ->editColumn('is_protected', function ($r) {
                    return ($r->is_protected) ? 'Yes' : 'No';
                })
                ->addColumn('action', function ($r) {
                    return view('admin::customizations.loan-type.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }
    public function createLoanType(Request $request,LoanType $loantype)
    {
        if($request->isMethod('post')) {
            $data = $request->all();
            $validator = Validator::make($data, [
                'descrip' => 'required|max:255',
            ]);
            if ($validator->fails()) {
                return redirect()->route('admin.appraisal.loantype.create')
                    ->withErrors($validator)
                    ->withInput();
            }
            $create = $loantype->create($data);
            if ($create) {
                Session::flash('success', 'Loan Type Created.');
                return redirect()->route('admin.appraisal.loantype');
            }
        }
        return view('admin::customizations.loan-type.create', compact('loantype'));
    }
    public function updateLoanType(Request $request, $id)
    {
        if ($request->isMethod('put')) {
            $data = $request->all();
            $validator = Validator::make($data, [
                'descrip' => 'required|max:255',
            ]);
            if ($validator->fails()) {
                return redirect()->route('admin.appraisal.loantype.update',[$id])
                    ->withErrors($validator)
                    ->withInput();
            }
            $loantype = LoanType::find($request->id);
            $update = $loantype->update($data);
            if ($update) {
                Session::flash('success', 'Loan Type Updated.');
                return redirect()->route('admin.appraisal.loantype');
            }
        }
        $loantype = LoanType::find($id);
        return view('admin::customizations.loan-type.create', compact('loantype'));
    }
    public function deleteLoanType($id)
    {
        $loanType = LoanType::find($id);
        if($loanType->is_protected){
            Session::flash('error', 'You cannot delete that item');
            return redirect()->route('admin.appraisal.loantype');
        }
        $loanType->delete();
        Session::flash('success', 'Loan Type Deleted.');
        return redirect()->route('admin.appraisal.loantype');
    }
}