<?php

namespace Modules\Admin\Repositories\ManagerReport;
use App\Models\Appraisal\Order;

class ReconsiderationRepository
{   
       
    /**
     * get status
     *
     * @return void
     */
    public function generateDataForDownload($dateRange)
    {

        $dateRange = explode("-", $dateRange);

        // Init
        $dateFrom = date('Y-m-d', strtotime($dateRange[0]));
        $dateTo = date('Y-m-d', strtotime($dateRange[1]));

        // Convert to unix
        $from = strtotime($dateFrom . ' 00:00:00');
        $to = strtotime($dateTo . ' 23:59:59');

        $logs = \DB::select("SELECT * FROM reconsideration_log WHERE dts >= '".date('Y-m-d H:i:s', $from)."' AND dts <= '".date('Y-m-d H:i:s', $to)."' AND content='Started Reconsideration' GROUP BY orderid ORDER BY dts DESC");
        $items = [];

        if($logs && count($logs)) {

            foreach($logs as $log) {
                
                $order = Order::getApprOrderById($log->orderid);
                $qc = \DB::selectOne("SELECT * FROM appraisal_checklist LEFT JOIN user_data ON (appraisal_checklist.adminid=user_data.user_id) WHERE orderid='".$log->orderid."'");

                $started_review = \DB::selectOne("SELECT * FROM reconsideration_log WHERE orderid='".$log->orderid."' AND dts >= '".date('Y-m-d H:i:s', $from)."' AND content='Started Reconsideration'");
                $rev = \DB::selectOne("SELECT * FROM reconsideration_log WHERE orderid='".$log->orderid."' AND dts >= '".date('Y-m-d H:i:s', $from)."' AND content LIKE '%Review Status Cleared%'");

                $qcNew = \DB::selectOne("SELECT * FROM appr_qc WHERE order_id='' ORDER BY id ASC");

                $qcDate = '--';
                if ($qc) {
                    $qcDate = date("m/d/Y H:i", strtotime($qc->dts));
                } elseif($qcNew) {
                    $qcDate = date("m/d/Y H:i", $qcNew->created_date);
                }

                $reviewDate = '--';
                $startValue = '--';
                $endValue = '--';
                $dateCleared = '--';
                if ($started_review) {
                    $reviewDate = date("m/d/Y H:i", strtotime($started_review->dts));
                }

                if ($order->review_startvalue) {
                    $startValue = $order->review_startvalue;
                }

                if ($order->review_endvalue) {
                    $endValue = $order->review_endvalue;
                }

                if ($rev) {
                    $dateCleared = date("m/d/Y H:i", strtotime($rev->dts));
                }

                $clientCompany  = $order->groupData ? $order->groupData->company : 'N/A';
                
                $items[] = array(
                    'order_id' => $order->id,
                    'qc_date' => $qcDate,
                    'client' => $clientCompany,
                    'loan_ref_number' => $order->loanrefnum,
                    'borrower' => $order->borrower,
                    'property_address' => ucwords(strtolower($order->propaddress1)),
                    'date_review_requested' => $reviewDate,
                    'original_value' => $startValue,
                    'date_review_cleared' => $dateCleared,
                    'ending_value' => $endValue,
                );
            }
        }

        return $items;
    }


    public function csvHeaders()
    {
        return [
            'order_id' => 'Order ID',
            'qc_date' => 'QC Date',
            'client' => 'Client',
            'loan_ref_number' => 'Loan Ref Number',
            'borrower' => 'Borrower',
            'property_address' => 'Property Address',
            'date_review_requested' => 'Date Review Requested',
            'original_value' => 'Original Value',
            'date_review_cleared' => 'Date Review Cleared',
            'ending_value' => 'Ending Value'
        ];
    }
   
}    