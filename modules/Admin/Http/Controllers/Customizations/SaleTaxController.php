<?php

namespace Modules\Admin\Http\Controllers\Customizations;

use App\Models\Customizations\SaleTax;
use App\Models\Geo\State;
use App\Models\Management\ZipCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\Customizations\SaleTaxRequest;
use Yajra\DataTables\Datatables;
use DB, Html;

class SaleTaxController extends AdminBaseController
{

    /**
     * Get a list of states
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::customizations.sale-tax.index');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            $states = ZipCode::select(['state', DB::raw('COUNT(DISTINCT county) as counties')])->whereIn('state', getStates(true))->groupBy('state')->get();
            return Datatables::of($states)
                ->addColumn('name', function ($r) {
                    return Html::linkRoute('admin.management.sale.tax.edit', getStateByAbbr($r->state) ?? $r->state, ['state' => $r->state]);
                })
                ->make(true);
        }
    }


    /**
     * @param SaleTaxRequest $request
     * @param SaleTax $saleTax
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(SaleTax $saleTax)
    {
        return view('admin::customizations.sale-tax.create', compact('saleTax'));
    }

    /**
     * Get counties for the state
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function counties(Request $request)
    {
        $state = $request->input('state');
        $counties = ZipCode::select('county')->whereState($state)->groupBy('county')
            ->orderBy('county')->get()->pluck('county');
        return view('admin::customizations.sale-tax.partials._counties', compact('counties'));
    }

    /**
     * Edit taxes for state
     * @param $state
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($state)
    {
        $saleTax = new SaleTax();
        $taxes = SaleTax::select(['county', 'value'])->whereState($state)
            ->groupBy('county')->get()->mapWithKeys(function ($item) {
                return [$item['county'] => $item['value']];
            });
        $counties = ZipCode::select('county')->whereState($state)->groupBy('county')
            ->orderBy('county')->get()->pluck('county');
        return view('admin::customizations.sale-tax.create', compact('saleTax', 'counties', 'state', 'taxes'));
    }

    /**
     * Update taxes
     * @param SaleTaxRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SaleTaxRequest $request)
    {
        if ($request->isMethod('put') or $request->isMethod('post')) {
            $counties = $request->input('county');
            $state = $request->input('state');
            foreach($counties as $county => $value) {
              if(!$value) {
                continue;
              }
              $saleTax = SaleTax::updateOrCreate(['state' => $state, 'county' => $county], ['value' => $value]);
            }
            Session::flash('success', 'Sale Tax Updated.');
        }
        return redirect()->route('admin.management.sale.tax.index');
    }

}
