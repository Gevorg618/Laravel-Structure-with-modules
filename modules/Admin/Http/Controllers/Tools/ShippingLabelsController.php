<?php

namespace Modules\Admin\Http\Controllers\Tools;

use App\Models\Tools\ShippingAddress;
use App\Models\Tools\ShippingItem;
use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Yajra\DataTables\Datatables;

class ShippingLabelsController extends AdminBaseController
{
    public function index()
    {
        $count = [];
        $count['appraisal'] = ShippingItem::orderType('appraisal')->count();
        $count['docuvault'] = ShippingItem::orderType('docuvault')->count();
        return view('admin::tools.shipping-labels.index2',compact('count'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function shippingLabelsData(Request $request)
    {
        if ($request->ajax()) {
            $shippingLabels = ShippingItem::orderType($request->get('orderType'))->get();
            return Datatables::of($shippingLabels)
                ->addColumn('checkbox',function ($r){
                    return $r->label_img;
                })
                ->editColumn('address', function ($r) {
                    $address = $r->shippingAddress->keyBy('address_type')->map(function ($item) {
                        return getStateByAbbr($item->state) . ', ' . $item->city . ', ' . $item->street1 . ', ' . $item->street2;
                    });
                    return '<span class="address">From: </span>' . $address['from'] . '<br>' .
                    '<span class="address">To: </span>' . $address['to'];
                })
                ->editColumn('created_date', function ($r) {
                    return toDate($r->created_date);
                })
                ->editColumn('tracking_number', function ($r) {
                    return '<a target="_blank" href="https://tools.usps.com/go/TrackConfirmAction?tLabels=' . $r->tracking_number . '">' . $r->tracking_number . '</a>';
                })
                ->editColumn('created_by', function ($r) {
                    return ($r->userData) ? $r->userData->firstname . ' ' . $r->userData->lastname : $r->created_by;
                })
                ->addColumn('action', function ($r) {
                    return '<a target="_blank" href="' . $r->label_pdf . '"><button type="button" class="btn btn-success">Download PDF</button></a>';
                })
                ->make(true);
        }
    }

    /**
     * @return mixed
     */
    public function downloadPDF(Request $request)
    {
        if($request->method() == 'POST'){
            $data['images'] = $request->get('images');
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('admin::tools.shipping-labels.partials._pdf',$data)->setPaper('letter', 'landscape')->setWarnings(false);
            return $pdf->stream(str_random(8).'.pdf');
        }
    }
}
