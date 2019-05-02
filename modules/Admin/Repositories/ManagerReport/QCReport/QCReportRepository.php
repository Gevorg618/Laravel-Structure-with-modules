<?php

namespace Modules\Admin\Repositories\ManagerReport\QCReport;

use Carbon\Carbon;
use App\Models\Appraisal\OrderLog;
use App\Models\ManagerReports\QCReport\{ QCReport, ApprQcLog };


class QCReportRepository
{
    /**
    * get Report
    * @return collection
    */
    public function getReport($request)
    {
        $date = $request->datefrom;
		$dateStartUnix = Carbon::parse($date)->startOfDay()->timestamp;
		$dateEndUnix = Carbon::parse($date)->endOfDay()->timestamp;
        $rows = $this->getProductivityReportData($dateStartUnix, $dateEndUnix);
        return $rows;
    }

    /**
    * get For Download
    * @return collection
    */
    public function getForDownload($request)
    {
        $date = $request->datefrom;
		$dateStartUnix = Carbon::parse($date)->startOfDay()->timestamp;
		$dateEndUnix = Carbon::parse($date)->endOfDay()->timestamp;
        $rows = $this->getProductivityReportData($dateStartUnix, $dateEndUnix);
        return $rows;
    }

    /**
    * get Productivity Report Data
    * @return array
    */
    public function getProductivityReportData($dateStartUnix, $dateEndUnix)
    {
        // Get all QC
        $rows = QCReport::whereBetween('created_date', [$dateStartUnix, $dateEndUnix])->get();
        $total = $rows ? count($rows) : 0;
        $times = $this->getProductivityReportTimes();
        $items = [];

        if ($rows) {
            foreach ($rows as $row) {
                $d = $row->created_date;
                $date = Carbon::createFromTimestamp($d)->format('m/d/Y');
                $time = Carbon::createFromTimestamp($d)->format('H:i');
                $timeRecord = $this->getProductivityReportTimeRecord($time);
                $timeKey = $timeRecord['time'];
                $items[] = [
                    'id' => $row->id,
                    'date' => $date,
                    'dateUnix' => $d,
                    'time' => $time,
                    'first_approved' => $row->first_approved,
                    'second_approved' => $row->second_approved,
                    'sent_back' => $row->sent_back,
                    'is_saved' => $row->is_saved,
                    'key' => $timeKey,
                    'keyFrom' => $timeRecord['from'],
                    'keyTo' => $timeRecord['to'],
                    'user' => $row->created_by,
                    'username' => getUserFullNameById($row->created_by),
                ];
            }
        }
        // Build records
        $records = [];
        foreach ($times as $d) {
            $records[$d['time']] = ['first_approved' => 0, 'second_approved' => 0, 'sent_back' => 0, 'is_saved' => 0, 'total' => 0, 'reports' => 0];
        }
        $records['N/A'] = ['first_approved' => 0, 'second_approved' => 0, 'sent_back' => 0, 'is_saved' => 0, 'total' => 0, 'reports' => 0];

        foreach ($times as $timeRange) {
            // Build reports date range
            $reportsFrom = sprintf("%s %s", Carbon::createFromTimestamp($dateStartUnix)->format('Y-m-d'), $timeRange['from']);
            $reportsTo = sprintf("%s %s", Carbon::createFromTimestamp($dateEndUnix)->format('Y-m-d'), $timeRange['to']);
            $reportsFromUnix = Carbon::parse($reportsFrom)->timestamp;
            $reportsToUnix = Carbon::parse($reportsTo)->timestamp;
            $reportsUploaded = $this->getAppraisalUploadsTotals($reportsFromUnix, $reportsToUnix);
            $records[ $timeRange['time'] ]['reports'] = $reportsUploaded['uploaded'];
        }

        if ($items && count($items)) {
            foreach ($items as $item) {
                $records[ $item['key'] ]['first_approved'] += $item['first_approved'];
                $records[ $item['key'] ]['second_approved'] += $item['second_approved'];
                $records[ $item['key'] ]['sent_back'] += $item['sent_back'];
                $records[ $item['key'] ]['is_saved'] += $item['is_saved'];
                $records[ $item['key'] ]['total'] += 1;
            }
        }
        return $records;
    }

    /**
    * get Productivity Report Times
    * @return array
    */
    public function getProductivityReportTimes()
    {
        return [
            ['time' => '12:00 AM - 5:00 AM',  'from' => '00:00', 'to' => '05:00'],
            ['time' => '5:00 AM - 6:00 AM',   'from' => '05:00', 'to' => '06:00'],
            ['time' => '6:00 AM - 7:00 AM',   'from' => '06:00', 'to' => '07:00'],
            ['time' => '7:00 AM - 8:00 AM',   'from' => '07:00', 'to' => '08:00'],
            ['time' => '8:00 AM - 9:00 AM',   'from' => '08:00', 'to' => '09:00'],
            ['time' => '9:00 AM - 10:00 AM',  'from' => '09:00', 'to' => '10:00'],
            ['time' => '10:00 AM - 11:00 AM', 'from' => '10:00', 'to' => '11:00'],
            ['time' => '11:00 AM - 12:00 PM', 'from' => '11:00', 'to' => '12:00'],
            ['time' => '12:00 PM - 01:00 PM', 'from' => '12:00', 'to' => '13:00'],
            ['time' => '01:00 PM - 02:00 PM', 'from' => '13:00', 'to' => '14:00'],
            ['time' => '02:00 PM - 03:00 PM', 'from' => '14:00', 'to' => '15:00'],
            ['time' => '03:00 PM - 04:00 PM', 'from' => '15:00', 'to' => '16:00'],
            ['time' => '04:00 PM - 05:00 PM', 'from' => '16:00', 'to' => '17:00'],
            ['time' => '05:00 PM - 06:00 PM', 'from' => '17:00', 'to' => '18:00'],
            ['time' => '06:00 PM - 07:00 PM', 'from' => '18:00', 'to' => '19:00'],
            ['time' => '07:00 PM - 08:00 PM', 'from' => '19:00', 'to' => '20:00'],
            ['time' => '08:00 PM - 09:00 PM', 'from' => '20:00', 'to' => '21:00'],
            ['time' => '09:00 PM - 10:00 PM', 'from' => '21:00', 'to' => '22:00'],
            ['time' => '10:00 PM - 11:00 PM', 'from' => '22:00', 'to' => '23:00'],
            ['time' => '11:00 PM - 11:59 PM', 'from' => '23:00', 'to' => '24:00'],
        ];
    }

    /**
    * get Productivity Report Time Record
    * @return array
    */
    public function getProductivityReportTimeRecord($time)
    {
        $times = $this->getProductivityReportTimes();
        foreach ($times as $r) {
            if ($time >= $r['from'] && $time <= $r['to']) {
                return $r;
            }
        }
        return 'N/A';
    }

    /**
    * get Appraisal Uploads Totals
    * @return array
    */
    public function getAppraisalUploadsTotals($from, $to)
    {
        $total_approved = ApprQcLog::select(\DB::raw('COUNT(DISTINCT order_id) as total'))
                                        ->where('created_date', '>=', $from)
                                        ->where('created_date', '<=', $to)
                                        ->where('is_approved', 1)->first();

        $total_uploaded = OrderLog::select(\DB::raw('COUNT(DISTINCT orderid) as total'))
                                        ->whereRaw(\DB::raw('info REGEXP "(Appraiser|Admin) Uploaded Appraisal rev [0-9]{1,3}"'), null)
                                        ->where('dts', '>=', date('Y-m-d H:i:s', $from))
                                        ->where('dts', '<=', date('Y-m-d H:i:s', $to))->first();

        $lasthour_uploaded = OrderLog::select(\DB::raw('COUNT(DISTINCT orderid) as total'))
                                        ->whereRaw(\DB::raw('info REGEXP "(Appraiser|Admin) Uploaded Appraisal rev [0-9]{1,3}"'), null)
                                        ->where('dts', '>=', date('Y-m-d H:00:00'))
                                        ->where('dts', '<=', (date('Y-m-d H:00:00', (time() + 60 * 60) )))->first();

        return ['approved' => $total_approved->total, 'uploaded' => $total_uploaded->total, 'lasthour_uploaded' => $lasthour_uploaded->total];
    }
}
