<?php

namespace Modules\Admin\Http\Controllers\Accounting;

use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Modules\Admin\Http\Requests\Accounting\GeneralReportsRequest;
use Modules\Admin\Services\Accounting\GeneralReportsService;

/**
 * Class GeneralReportController
 * @package Modules\Admin\Http\Controllers
 */
class GeneralReportController extends Controller
{   
    /**
     * Object of GeneralReportsService class
     *
     * @var service
     */
    protected $service;

    /**
     * GeneralReportController constructor.
     * 
     * @param GeneralReportsService $service
     */
    public function __construct(GeneralReportsService $service)
    {
        $this->service = $service;
    }

    /**
     *  Index page view
     *  
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::accounting.general-reports.index', [
            'reports' => $this->service->getGeneralReportList(),
        ]);
    }

    /**
     * Download data general reports
     * 
     * @param GeneralReportsRequest $request
     */
    public function export(GeneralReportsRequest $request)
    {
        $data = $this->service->getData($request->post('report'));
        \Excel::create('accounting_report', function (LaravelExcelWriter $excel) use ($data) {
            $excel->sheet('Accounting report', function (LaravelExcelWorksheet $sheet) use ($data) {
                $sheet->fromModel($data);
            });
        })->download('xls');
    }
}
