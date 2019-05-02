<?php

namespace Modules\Admin\Http\Controllers\Accounting;

use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Modules\Admin\Http\Requests\Accounting\DailyBatchRequest;
use Modules\Admin\Services\Accounting\Batch\DailyBatchService;

/**
 * Class DailyBatchController
 * @package Modules\Admin\Http\Controllers
 */
class DailyBatchController extends Controller
{
    protected $service;

    /**
     * DailyBatchController constructor.
     * @param $service
     */
    public function __construct(DailyBatchService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::accounting.daily-batch.index');
    }

    /**
     * @param DailyBatchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apprCreditCards(DailyBatchRequest $request)
    {
        $from = $request->post('date_from');
        $to = $request->post('date_to');
        $type = $request->post('type');
        $query = $this->service->getAppraisalCards($from, $to, $type);
        return $this->service->appraisalCardsDataTables($query);
    }

    /**
     * @param DailyBatchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apprChecks(DailyBatchRequest $request)
    {
        $from = $request->post('date_from');
        $to = $request->post('date_to');
        $type = $request->post('type');
        $query = $this->service->getAppraisalChecks($from, $to, $type);
        return $this->service->appraisalChecksDataTables($query);
    }

    /**
     * @param DailyBatchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mercury(DailyBatchRequest $request)
    {
        $from = $request->post('date_from');
        $to = $request->post('date_to');
        $type = $request->post('type');
        $query = $this->service->getMercury($from, $to, $type);
        return $this->service->mercuryDataTables($query);
    }

    /**
     * @param DailyBatchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function altCreditCards(DailyBatchRequest $request)
    {
        $from = $request->post('date_from');
        $to = $request->post('date_to');
        $type = $request->post('type');
        $query = $this->service->getAltCreditCards($from, $to, $type);

        return $this->service->altCreditCardsDataTables($query);
    }

    /**
     * @param DailyBatchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function altChecks(DailyBatchRequest $request)
    {
        $from = $request->post('date_from');
        $to = $request->post('date_to');
        $type = $request->post('type');
        $query = $this->service->getAltChecks($from, $to, $type);
        return $this->service->altChecksDataTables($query);
    }

    /**
     * @param DailyBatchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function docuvaultChecks(DailyBatchRequest $request)
    {
        $from = $request->post('date_from');
        $to = $request->post('date_to');
        $type = $request->post('type');
        $query = $this->service->getDocuvaultChecks($from, $to, $type);
        return $this->service->docuvaultChecksDataTables($query);
    }

    /**
     * @param DailyBatchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdjustments(DailyBatchRequest $request)
    {
        $from = $request->post('date_from');
        $to = $request->post('date_to');
        $type = $request->post('type');
        $query = $this->service->getAdjustments($from, $to, $type);
        return $this->service->adjustmentsDataTables($query);
    }

    /**
     * @param DailyBatchRequest $request
     */
    public function export(DailyBatchRequest $request)
    {
        \Excel::create('daily-batch', function (LaravelExcelWriter $excel) use ($request) {
            $from = $request->post('date_from');
            $to = $request->post('date_to');
            $type = $request->post('type');
            list(
                $exportApprCards,
                $exportMercury,
                $exportApprChecks,
                $exportAltCreditCards,
                $exportAltChecks,
                $exportDocuvaultChecks,
                $exportAdjustments
            ) = $this->service->getExportData($from, $to, $type);
            $excel->sheet('Appraisal Credit Cards', function (LaravelExcelWorksheet $sheet) use ($exportApprCards) {
                $sheet->fromArray($exportApprCards);
            });
            $excel->sheet('Mercury', function (LaravelExcelWorksheet $sheet) use ($exportMercury) {
                $sheet->fromArray($exportMercury);
            });
            $excel->sheet('Appraisal Checks', function (LaravelExcelWorksheet $sheet) use ($exportApprChecks) {
                $sheet->fromArray($exportApprChecks);
            });
            $excel->sheet('Alt Credit Cards', function (LaravelExcelWorksheet $sheet) use ($exportAltCreditCards) {
                $sheet->fromArray($exportAltCreditCards);
            });
            $excel->sheet('Alt Checks', function (LaravelExcelWorksheet $sheet) use ($exportAltChecks) {
                $sheet->fromArray($exportAltChecks);
            });
            $excel->sheet('DocuVault Checks', function (LaravelExcelWorksheet $sheet) use ($exportDocuvaultChecks) {
                $sheet->fromArray($exportDocuvaultChecks);
            });
            $excel->sheet('Adjustments', function (LaravelExcelWorksheet $sheet) use ($exportAdjustments) {
                $sheet->fromArray($exportAdjustments);
            });
        })->download('xls');

    }
}
