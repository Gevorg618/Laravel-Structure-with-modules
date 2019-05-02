<?php
    
namespace Modules\Admin\Http\Controllers\Customizations;

use App\Models\DocuVault\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\Customizations\AppraisalRequest;
use Yajra\DataTables\Facades\Datatables;


class AppraisalController extends AdminBaseController
{
    /**
     * Index page for Appraisal
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::customizations.docu-vault-appraisal.index');
    }
    
    /**
     * Gather data for index page and datatables
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            $appraisals = Type::all();
            
            return Datatables::of($appraisals)
                ->editColumn('is_active', function ($r) {
                    return ($r->is_active) ? 'Yes' : 'No';
                })
                ->addColumn('action', function ($r) {
                    return view('admin::customizations.docu-vault-appraisal.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }
    
    /**
     * Create new appraisal
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin::customizations.docu-vault-appraisal.create');
    }
    
    
    public function store(AppraisalRequest $request, Type $appraisal)
    {
        $appraisal->store($request);
        Session::flash('success', 'DocuVault Appraisal Type Successfully Created.');
        
        return redirect()->route('admin.docuvault.appraisal.index');
    }
    
    /**
     * Edit property type details
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $appraisal = Type::findOrFail($id);
        
        return view('admin::customizations.docu-vault-appraisal.edit', compact('appraisal'));
    }
    
    /**
     * Update appraisal type
     *
     * @param AppraisalRequest $request
     * @param Type $Type
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AppraisalRequest $request, Type $appraisal)
    {
        $appraisal->store($request);
        Session::flash('success', 'DocuVault Appraisal Type Successfully Updated.');
        
        return redirect()->route('admin.docuvault.appraisal.index');
    }
    
    /**
     * Delete DocuVault type
     *
     * @param Type $Type
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Type $appraisal)
    {
        Session::flash('success', 'DocuVault Appraisal Type is deleted.');
        $appraisal->delete();
        
        return redirect()->route('admin.docuvault.appraisal.index');
    }
}
