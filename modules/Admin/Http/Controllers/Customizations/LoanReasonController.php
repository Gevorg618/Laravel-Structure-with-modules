<?php

namespace Modules\Admin\Http\Controllers\Customizations;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\AdminBaseController;
use App\Models\Customizations\LoanReason;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Requests\Customizations\LoanReasonRequest;

class LoanReasonController extends AdminBaseController
{
    public function index()
    {
        return view('admin::customizations.loan-reason.index');
    }

    /**
     * Process datatables ajax request.
     * @param Request $request
     * @return mixed
     */
    public function loanReasonData(Request $request)
    {
        if ($request->ajax()) {
            $loanreason = LoanReason::all();
            return Datatables::of($loanreason)
                ->editColumn('is_protected', function ($r) {
                    return ($r->is_protected) ? 'Yes' : 'No';
                })
                ->addColumn('action', function ($r) {
                    return view('admin::customizations.loan-reason.partials._loan_reasons_options', ['row' => $r]);
                })
                ->make(true);
        }
    }

    /**
     * create Alternative Appraisal LoanReason
     * @param Request $request
     * @param LoanReason $status
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function createLoanReason(LoanReasonRequest $request, LoanReason $loanreason)
    {
        if($request->isMethod('post')) {
            $data = $request->all();
            $create = $loanreason->create($data);
            if ($create) {
                Session::flash('success', 'Loan Reason Created.');
                return redirect()->route('admin.appraisal.loanreason');
            }
        }
        return view('admin::customizations.loan-reason.create', compact('loanreason'));
    }

    /**
     * @param Request $request
     * @param LoanReason $loanreason
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function updateLoanReason(LoanReasonRequest $request, LoanReason $loanreason)
    {
        if ($request->isMethod('put')) {
            $data = $request->all();

            $update = $loanreason->update($data);
            if ($update) {
                Session::flash('success', 'Loan Reason Updated.');
            }
        }
        return redirect()->route('admin.appraisal.loanreason');
    }

    /**
     * @param LoanReason $loanreason
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function deleteLoanReason(LoanReason $loanreason)
    {
        if($loanreason->is_protected){
            Session::flash('error', 'You cannot delete that item');
            return redirect()->route('admin.appraisal.loanreason');
        }
        $loanreason->delete();
        Session::flash('success', 'Loan Reason Deleted.');
        return redirect()->route('admin.appraisal.loanreason');
    }
}