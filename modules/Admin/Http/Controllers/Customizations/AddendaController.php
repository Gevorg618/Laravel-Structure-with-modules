<?php

namespace Modules\Admin\Http\Controllers\Customizations;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\AdminBaseController;
use App\Models\Customizations\Addenda;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Requests\Customizations\AddendaRequest;

class AddendaController extends AdminBaseController
{
    public function index()
    {
        return view('admin::customizations.addendas.index');
    }

    /**
     * Process datatables ajax request.
     * @param Request $request
     * @return mixed
     */
    public function addendasData(Request $request)
    {
        if ($request->ajax()) {
            $status = Addenda::all();
            return Datatables::of($status)
                ->editColumn('is_protected', function ($r) {
                    return ($r->is_protected) ? 'Yes' : 'No';
                })
                ->addColumn('action', function ($r) {
                    return view('admin::customizations.addendas.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }

    /**
     * @param AddendaRequest $request
     * @param Addenda $addenda
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function createAddenda(AddendaRequest $request,Addenda $addenda)
    {
        if($request->isMethod('post')) {
            $data = $request->all();
            $create = $addenda->create($data);
            if ($create) {
                Session::flash('success', 'Addenda Created.');
                return redirect()->route('admin.appraisal.addendas');
            }
        }
        return view('admin::customizations.addendas.create', compact('addenda'));
    }

    /**
     * @param AddendaRequest $request
     * @param Addenda $addenda
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAddenda(AddendaRequest $request, Addenda $addenda)
    {
        if ($request->isMethod('put')) {
            $data = $request->all();
            $update = $addenda->update($data);
            if ($update) {
                Session::flash('success', 'Addenda Updated.');
            }
        }
        return redirect()->route('admin.appraisal.addendas');
    }

    /**
     * @param Addenda $addenda
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function deleteAddenda(Addenda $addenda)
    {
        if($addenda->is_protected){
            Session::flash('error', 'You cannot delete that item');
            return redirect()->route('admin.appraisal.addendas');
        }
        $addenda->delete();
        Session::flash('success', 'Addenda Deleted.');
        return redirect()->route('admin.appraisal.addendas');
    }
}