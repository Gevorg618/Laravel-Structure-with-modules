<?php

namespace Modules\Admin\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Modules\Admin\Http\Requests\Accounting\ReportRequest;
use Modules\Admin\Services\Accounting\ReportsService;

class ReportController extends Controller
{   
    /**
     * Object of ReportsService class
     *
     * @var service
     */
    protected $service;

    /**
     * ReportController constructor.
     * @param $service
     */
    public function __construct(ReportsService $service)
    {
        $this->service = $service;
    }

    /**
     *  index page of report 
     *  
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::accounting.reports.index', [
            'dateTypes' => $this->service->getDateTypes(),
            'reports' => $this->service->getReportList(),
        ]);
    }

    /**
     * render view 
     * 
     * @param ReportRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(ReportRequest $request)
    {
        $dateFrom = $request->post('date_from');
        $dateTo = $request->post('date_to');
        $dateType = $request->post('date_type');
        $report = $request->post('report');
        $result = $this->service->getReportResult($report, $dateFrom, $dateTo, $dateType);
        return response()->json([
            'html' => view('admin::accounting.reports.partials.' . $report, [
                'result' => $result
            ])->render(),
        ]);
    }

    /**
     * download csv file of report data
     * 
     * @param ReportRequest $request
     */
    public function export(ReportRequest $request)
    {
        $dateFrom = $request->post('date_from');
        $dateTo = $request->post('date_to');
        $dateType = $request->post('date_type');
        $report = $request->post('report');
        $result = $this->service->getReportResult($report, $dateFrom, $dateTo, $dateType);
        \Excel::create($report, function (LaravelExcelWriter $excel) use ($result, $report) {
            $excel->sheet('Report', function (LaravelExcelWorksheet $sheet) use ($result, $report) {
                if ($report != 'payments_collected') {
                    $sheet->fromArray([$result]);
                } else {
                    $sheet->fromArray($result);
                }
            });
        })->download('xls');
    }
}
