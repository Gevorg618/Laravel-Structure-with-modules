<?php

namespace Modules\Admin\Http\Controllers\Accounting;

use App\Models\Clients\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Modules\Admin\Http\Requests\Accounting\AlPayableReportRequest;
use Modules\Admin\Services\Accounting\AlPayableReportService;

class AlPayableReportController extends Controller
{
    protected $service;

    /**
     * AlPayableReportController constructor.
     * @param AlPayableReportService $service
     */
    public function __construct(AlPayableReportService $service)
    {
        $this->service = $service;
    }

    /**
     * @param AlPayableReportRequest $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index (AlPayableReportRequest $request)
    {
        
        $view = view('admin::accounting.al-payable-reports.index', [
            'states' => getStates(),
            'clients' => Client::pluck('descrip', 'id'),
        ]);
        if ($request->filled('submit') || $request->filled('export')) {

            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');
            $states = $request->get('states');
            $clients = $request->get('clients');
           
            list($rows, $totalSum, $totalBalance) = $this->service->getData(
                $dateFrom,
                $dateTo,
                $states,
                $clients
            );
            
            $view = $view->with([
                'rows' => $rows,
                'totalSum' => $totalSum,
                'statuses' => $this->service->getALOrderStatuses(),
                'totalBalance'  => $totalBalance
            ]);

            if ($request->filled('export')) {
                $items = $this->service->makeDataForExport($rows);
                \Excel::create('al_accounts_payable', function (LaravelExcelWriter $excel) use ($items) {
                    $excel->sheet('AL Accounts Payable', function (LaravelExcelWorksheet $sheet) use ($items) {
                        $sheet->fromArray($items);
                    });
                })->download('xls');
            }
        }
        return $view;
    }
}
