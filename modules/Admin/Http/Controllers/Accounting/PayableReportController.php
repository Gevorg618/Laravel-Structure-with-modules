<?php

namespace Modules\Admin\Http\Controllers\Accounting;

use App\Models\Clients\Client;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Modules\Admin\Http\Requests\Accounting\PayableReportRequest;
use Modules\Admin\Services\Accounting\PayableReportService;

/**
 * Class PayableReportController
 * @package Modules\Admin\Http\Controllers
 */
class PayableReportController extends Controller
{
    /**
     * @var PayableReportService
     */
    protected $service;

    /**
     * PayableReportController constructor.
     * @param $service
     */
    public function __construct(PayableReportService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::accounting.payable-reports.index', [
            'states' => getStates(),
            'clients' => Client::pluck('descrip', 'id'),
        ]);
    }

    /**
     * @param PayableReportRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(PayableReportRequest $request)
    {
        $dateFrom = $request->post('date_from');
        $dateTo = $request->post('date_to');
        $clients = $request->post('clients');
        $states = $request->post('states');
        $appraisals = $this->service->getData($dateFrom, $dateTo, $clients, $states);
        return response()->json([
            'html' => view('admin::accounting.payable-reports.partials.results', [
                'appraisals' => $appraisals,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
            ])->render(),
        ]);
    }

    /**
     * @param PayableReportRequest $request
     */
    public function export(PayableReportRequest $request)
    {
        $dateFrom = $request->post('date_from');
        $dateTo = $request->post('date_to');
        $clients = $request->post('clients');
        $states = $request->post('states');
        $appraisals = $this->service->getData($dateFrom, $dateTo, $clients, $states);
        \Excel::create('accounts_payable_report', function (LaravelExcelWriter $excel) use ($appraisals) {
            $excel->sheet('Accounts Payable Report', function (LaravelExcelWorksheet $sheet) use ($appraisals) {
                $sheet->fromModel($appraisals);
            });
        })->download('xls');
    }
}
