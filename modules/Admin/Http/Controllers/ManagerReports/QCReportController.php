<?php

namespace Modules\Admin\Http\Controllers\ManagerReports;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Repositories\ManagerReport\QCReport\QCReportRepository;

class QCReportController extends AdminBaseController
{
    /**
    * QC Report Generator index
    * @return view
    */
    public function index()
    {
        $data = Session::get('rows');
        if (is_null($data)) {
            $rows = false;
            $date = '';
        } else {
            $rows = $data;
            $date = Session::get('date');
            Session::forget('rows');
            Session::forget('date');
        }
        return view('admin::manager-reports.qc-report.index', compact('rows', 'date'));
    }

    /**
    * QC Report Generator data
    * @return view
    */
    public function form(
            Request $request,
            QCReportRepository $qcReportRepository
        )
    {
        if ($request->has('submit')) {
            $date = $request->datefrom;
            $rows = $qcReportRepository->getReport($request);
            Session::put('rows', $rows);
            Session::put('date', $date);
            return redirect(route('admin.manager-reports.qc-report'));
        } elseif($request->has('download')) {

            return $this->download($request, $qcReportRepository);

        }

    }

    /**
    * QC Report Generator download
    * @return view
    */
    public function download($request, $qcReportRepository)
    {
		$rows = $qcReportRepository->getForDownload($request);
        $date = $request->datefrom;
        // Create csv
		$lines = [];
		$lines[] = "Time Range,Reports Uploaded,1st Approved,Sent Back,2nd Approved,Save/Hold";

		foreach($rows as $range => $data) {
			$lines[] = sprintf("%s,%s,%s,%s,%s,%s", $range, $data['reports'], $data['first_approved'], $data['sent_back'], $data['second_approved'], $data['is_saved']);
		}
		$name = sprintf("QC-Report-%s.csv", Carbon::parse($date)->format('m_d_Y'));

		// Download file
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=" . $name);
		header("Pragma: no-cache");
		header("Expires: 0");
		echo implode("\n", $lines);
		exit;
    }

}
