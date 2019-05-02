<?php

namespace Modules\Admin\Http\Controllers\Customizations;

use Illuminate\Support\Facades\App;
use Modules\Admin\Http\Controllers\AdminBaseController;
use App\Models\Customizations\AccessType;
use Modules\Admin\Http\Requests\Customizations\ApprAccessTypeRequest;
use Yajra\DataTables\Datatables;
use Html, Session;


class AccessTypeController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin::customizations.access-type.index');
    }

    /**
     * Get JSON with all AccessTypes rows
     * @return JSON
     */
    public function getData()
    {
        $rows = AccessType::all();

        return Datatables::of($rows)
            ->editColumn('name', function ($r) {
                return Html::linkRoute('admin.appraisal.access_type.edit', $r->name, ['id' => $r->id]);
            })
            ->editColumn('is_active', function ($r) {
                return ($r->is_active) ? 'Active' : 'Not active';
            })
            ->addColumn('action', function ($r) {
                return view('admin::customizations.access-type.partials.options', ['row' => $r]);
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $row = new AccessType();

        // Display form
        return view('admin::customizations.access-type.form', ['row' => $row]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApprAccessTypeRequest $request)
    {
        $access_type = new AccessType();
        $saved = $access_type->fill($request->all())->save();

        if ($saved) {
            Session::flash('success', 'Access Type Saved.');
            return redirect()->route('admin.appraisal.access_type.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $row = AccessType::findOrFail($id);
        return view('admin::customizations.access-type.form', ['row' => $row]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ApprAccessTypeRequest $request, $id)
    {
        $row = AccessType::find($id);
        $row->fill($request->all())->update();

        Session::flash('success', 'Access Type updated.');
        return redirect()->route('admin.appraisal.access_type.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $row = AccessType::findOrFail($id);
        $row->delete();
        Session::flash('success', 'Access Type deleted.');
        return redirect()->route('admin.appraisal.access_type.index');
    }
}