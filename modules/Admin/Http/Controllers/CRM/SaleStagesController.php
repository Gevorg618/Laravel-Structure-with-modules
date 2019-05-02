<?php
namespace Modules\Admin\Http\Controllers\CRM;

use Illuminate\Http\Request;
use App\Http\Requests;
use Modules\Admin\Http\Controllers\AdminBaseController;
use App\Models\CRM\SaleStage;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Requests\CRM\SaleStagesRequest;

class SaleStagesController extends AdminBaseController
{
    /**
     * Get index page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::crm.sale-stages.index');
    }

    /**
     * Create new Sale Stage
     * @param SaleStagesRequest $request
     * @param SaleStage $saleStage
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(SaleStagesRequest $request, SaleStage $saleStage)
    {
        if($request->isMethod('post')) {
            $data = $request->all();
            $create = $saleStage->create($data);
            if ($create) {
                Session::flash('success', 'Sale Stage Created.');
                return redirect()->route('admin.crm.sale.stages.index');
            }
        }
        return view('admin::crm.sale-stages.edit', compact('saleStage'));
    }

    /**
     * Get all Sale Stages by Ajax
     * @param Request $request
     * @return mixed
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            $saleStages = SaleStage::all();
            return Datatables::of($saleStages)
                ->editColumn('visible', function ($r) {
                    return $r->visible ? 'Visible' : 'Not visible';
                })
                ->addColumn('action', function ($r) {
                    return view('admin::crm.sale-stages.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }

    /**
     * Show edit page for Sale Stage
     * @param SaleStage $saleStage
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(SaleStage $saleStage)
    {
        return view('admin::crm.sale-stages.edit', compact('saleStage'));
    }

    /**
     * Update data for Sale Stage
     * @param SaleStagesRequest $request
     * @param SaleStage $saleStage
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SaleStagesRequest $request, SaleStage $saleStage)
    {
        if ($request->isMethod('put')) {
            $data = $request->all();
            $update = $saleStage->update($data);
            Session::flash('success', 'Sale Stage Updated.');
        }
        return redirect()->route('admin.crm.sale.stages.index');
    }

    /**
     * Soft delete the Sale Stage
     * @param SaleStage $saleStage
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(SaleStage $saleStage)
    {
        $saleStage->delete();
        Session::flash('success', 'Sale Stage Deleted.');
        return redirect()->route('admin.crm.sale.stages.index');
    }
}