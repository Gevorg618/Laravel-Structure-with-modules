<?php

namespace Modules\Admin\Http\Controllers\Accounting;

use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Modules\Admin\Http\Requests\Accounting\ExportCheckRequest;
use Modules\Admin\Repositories\Accounting\CimCheckPaymentRepository;

class ExportCheckController extends Controller
{
    protected $cimCheckRepo;

    /**
     * ExportCheckController constructor.
     * @param $orderRepo
     */
    public function __construct(CimCheckPaymentRepository $cimCheckRepo)
    {
        $this->cimCheckRepo = $cimCheckRepo;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::accounting.export-check.index');
    }

    /**
     * @param ExportCheckRequest $request
     */
    public function export(ExportCheckRequest $request)
    {
        $checkNumber = $request->post('check_number');
        $rows = $this->cimCheckRepo->getExportCheckData($checkNumber);
        \Excel::create('export_check', function (LaravelExcelWriter $excel) use ($rows) {
            $excel->sheet('Export Check', function (LaravelExcelWorksheet $sheet) use ($rows) {
                $sheet->fromModel($rows);
            });
        })->download('xls');
    }
}
