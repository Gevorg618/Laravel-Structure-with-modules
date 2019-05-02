<?php

namespace Modules\Admin\Http\Controllers\Accounting;

use PDF;
use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Repositories\Accounting\DocuVaultReceivablesRepository;
use Maatwebsite\Excel\Facades\Excel;

class DocuVaultReceivablesController extends AdminBaseController
{
    
    /**
     * Object of DocuVaultReceivablesRepository class
     *
     * @var docuVaultReceivablesRepo
     */
    private $docuVaultReceivablesRepo;
    
    /**
     * Create a new instance of DocuVaultReceivablesController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->docuVaultReceivablesRepo = new DocuVaultReceivablesRepository();
    }

    /**
     * Index page for Accounting Payable Revert
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $items = $this->docuVaultReceivablesRepo->docuVaultReceivablesDataTables();

        return view('admin::accounting.docuvault-receivables.index', compact('items'));
    }

    /**
     * show multiple and single orders group
     *
     * @param Request $request
     *
     * @return view
    */
    public function show(Request $request)
    {
        $items = $this->docuVaultReceivablesRepo->getClientRecordsListDataProvider($request->all());
        
        return view('admin::accounting.docuvault-receivables.orders', compact('items'));
    }

    /**
     * download data
     *
     * @param Request $request
     *
     * @return views
    */
    public function download(Request $request)
    {
        $dataJson = json_decode($request->get('data'));
        
        $dataCsv = $this->docuVaultReceivablesRepo->dataCsv($dataJson);

        if ($dataCsv) {
            Excel::create('Docuvault Receivables', function($excel) use($dataCsv) {

                $excel->sheet('sheet1', function($sheet) use ($dataCsv) {
                    
                     // Set Array
                    $sheet->fromArray($dataCsv);      
                   
               });
        
            })->download('xlsx');
        } else {
            \Session::flash('warning', 'There is no data for download !');
            return redirect()->route('admin::accounting.docuvault-receivables.index');
        }
    }

    /**
     * download the statments
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function statments(Request $request)
    {
        $dataJson = json_decode($request->get('data'));
        $items = $this->docuVaultReceivablesRepo->getDataForStatments($dataJson);

        $pdf = PDF::loadView('accounting.docuvault-receivables.partials.statments', [
            'items' => $items
        ]);

        return $pdf->setPaper('letter', 'landscape')->download('statments.pdf');
    }
    
}
