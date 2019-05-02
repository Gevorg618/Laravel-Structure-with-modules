<?php

namespace Modules\Admin\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Modules\Admin\Http\Requests\Accounting\VendorTaxInfoRequest;
use Modules\Admin\Repositories\Users\UserRepository;
use Modules\Admin\Services\Accounting\VendorTaxInfoService;

/**
 * Class VendorTaxInfoController
 * @package Modules\Admin\Http\Controllers
 */
class VendorTaxInfoController extends Controller
{
    protected $service;

    /**
     * VendorTaxInfoController constructor.
     * @param VendorTaxInfoService $service
     */
    public function __construct(VendorTaxInfoService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $years = range(date('Y') - 10, date('Y') + 20);
        return view('admin::accounting.vendor-tax-info.index', [
            'years' => array_combine($years, $years),
        ]);
    }

    /**
     * @param VendorTaxInfoRequest $request
     */
    public function export(VendorTaxInfoRequest $request)
    {
        $year = $request->post('year');
        $data = $this->service->getData($year);
        \Excel::create('vendor_tax_info', function (LaravelExcelWriter $excel) use ($data) {
            list($taxList, $duplicatedCompanies) = $data;
            $excel->sheet('Tax List', function (LaravelExcelWorksheet $sheet) use ($taxList) {
                $sheet->fromModel($taxList);
            });
            $excel->sheet('Duplicated Companies', function (LaravelExcelWorksheet $sheet) use ($duplicatedCompanies) {
                $sheet->fromModel($duplicatedCompanies);
            });
        })->download('xls');
    }

}
