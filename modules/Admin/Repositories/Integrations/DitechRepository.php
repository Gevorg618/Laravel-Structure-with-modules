<?php

namespace Modules\Admin\Repositories\Integrations;
use App\Models\Appraisal\Order;

class DitechRepository
{

	/**
     * Object of Order class
     *
     * @var $order
     */
    private $order;

    /**
     * DitechRepository constructor.
     */
    public function __construct()
    {
        $this->order = new Order();
    }

    /**
     * get items for download CSV
     * @param $data
     */
    public function getItems($data)
    {
        
        $dateRange = explode("-", $data['daterange']);
        
        $dateFrom = date('Y-m-d', strtotime($dateRange[0]));
        $dateTo = date('Y-m-d', strtotime($dateRange[1]));        
        
        $orders = Order::where('is_mercury' , 1)->whereBetween('ordereddate', [ $dateFrom, $dateTo]);
        
        $loanNumbers = $data['loanNumbers'];

        if($loanNumbers != '') {
            $loanNumbers = explode("\n", $loanNumbers);
            $orders = $orders->whereIn('loanrefnum', $loanNumbers);
        }
        $orders = $orders->get()->take(2000);
        $list = [];

        $headers = $this->includingHeaders();

        // Loop orders
        foreach($orders as $order) {            
            
            $delayedTimes = $this->getOrderDelayedDates($order->id);
            $delayedDays = 0;
            $delayNote = '';
            if($delayedTimes) {
                foreach($delayedTimes as $r) {
                    $delayedDays += $r['diff'];
                    if($r['note']) {
                        $delayNote = $r['note'];
                    }
                }
            }
            
            $sentBackToQC = $this->_getQCSentBackCount($order->id);
            
            $status = $order->apprStatus->first();
            
            $title = $status->descrip;
            $list[] = [
                'loannumber' => $order->loanrefnum,
                'state' => $order->propstate,
                'county' => $order->zipCode ? strtolower($order->zipCode->propzip) : '' ,
                'loanreason' => $order->loanpurpose,
                'appr_type_descrip' => $order->appraisalType ? $order->appraisalType->descrip : '',
                'appr_type_form' => $order->appraisalType ? $order->appraisalType->form : '',
                'is_jumbo' => $order->is_jumbo ? 'Yes' : 'No',
                'status' => $title,
                'placed_date' => date('m/d/Y H:i', strtotime($order->ordereddate)),
                'accepted_date' => date('m/d/Y H:i', strtotime($order->accepteddate)),
                'scheduled_date' => date('m/d/Y H:i', strtotime($order->schd_date)),
                'inspection_complete_date' => date('m/d/Y H:i', strtotime($order->completed)),
                'delivered_date' => date('m/d/Y H:i', strtotime($order->date_delivered)),
                'qc_items' => $sentBackToQC,
                'delay_days' => number_format($delayedDays),
                'last_delay_reason' => $delayNote,
                'replacement_report' => '',
                'refund_process' => '',
            ];
            
        }

        $dataCsv = [];

        foreach ($list as $key => $value) 
        {
            
            foreach ($headers as $keyHead => $valueHead) 
            {
                $dataCsv[$key][$valueHead] =  $value[$keyHead];
            }
        }
        
        return $dataCsv;
    }

    /**
     * headers
     * @param $data
     */
    public function includingHeaders()
    {
        return [
                'loannumber' => 'Loan Number',
                'state' => 'State',
                'county' => 'County',
                'loanreason' => 'Purchase / Refi',
                'appr_type_descrip' => 'Single Family / Multi Family',
                'appr_type_form' => 'Appraisal Type',
                'is_jumbo' => 'Loan Type (Jumbo / Non Jumbo)',
                'status' => 'Status',
                'placed_date' => 'Date Ditech Ordered',
                'accepted_date' => 'Appraiser Acceptance Date',
                'scheduled_date' => 'Property Insepction Set Date',
                'inspection_complete_date' => 'Property Inspection Complete Date',
                'delivered_date' => 'Date Ditech Received Appraisal',
                'qc_items' => '# of QCs AMC sends back to Appraiser',
                'delay_days' => 'Calendar Days Attributable to Holds and Delays (combine if there are multiple holds)',
                'last_delay_reason' => 'Reason for Hold or Delay (if multiple provide last reason)',
                'replacement_report' => 'Replacement Report',
                'refund_process' => 'Refund Processed',
        ];
    }

    /**
     * Number of times an order sent back to QC corrections
     *
     */
    protected  function _getQCSentBackCount($id) {
        $row = \DB::selectOne("SELECT COUNT(id) as total FROM appr_qc_stats WHERE order_id=:id AND sent_back=1", [':id' => $id]);
        return $row->total;
    }

    public function getOrderTurnTimeInMinutesByTurnTimeString($time)
    {
        if (!$time) {
            return 0;
        }

        preg_match('/(-?\d+) Mo (-?\d+) D (-?\d+) H (-?\d+) M/', $time, $matches);

        if (!count($matches) == 5) {
            return 0;
        }


        $months = $matches[1];
        $days = $matches[2];
        $hours = $matches[3];
        $minutes = $matches[4];

        $total = $days;

        if ($months) {
            $total += ($months * 31);
        }

        if ($hours) {
            $total += ($hours / 24);
        }

        if ($minutes) {
            $total += ($minutes / 24 / 60);
        }

        return number_format($total, 3);
    }

    public function getTotalNumberOfDays($from, $to) 
    {
        $diff = abs( $from - $to  );
        return ['d' => intval( $diff / 86400 ), 'h' => intval( ( $diff % 86400 ) / 3600), 'm' => intval( ( $diff / 60 ) % 60 ), 's' => intval( $diff % 60 )];
    }

    public function dateDiffHours($from, $to) {
        $diff = $this->getTotalNumberOfDays(strtotime($from), strtotime($to));
        return sprintf('0 Mo %s D %s H %s M', $diff['d'], $diff['h'], $diff['m']);
    }

    public function getOrderDelayedDates($orderId)
    {

        $items = [];
        $sql = "SELECT * FROM appr_order_delay_dates WHERE order_id='" . $orderId . "' AND start_date > 0 AND end_date > 0";
        $rows = \DB::select($sql);
        $exists = array();

        if ($rows) {
            foreach ($rows as $row) {
                $diff = $this->dateDiffHours(date('Y-m-d H:i', $row->start_date), date('Y-m-d H:i', $row->end_date));
                $items[] = array(
                    'start' => $row->start_date,
                    'end' => $row->end_date,
                    'start_human' => date('m/d/Y H:i:s', $row->start_date),
                    'end_human' => date('m/d/Y H:i:s', $row->end_date),
                    'diff_human' => $diff,
                    'diff' => $this->getOrderTurnTimeInMinutesByTurnTimeString($diff),
                    'note' => null,
                );
            }
        }

        $sql = "SELECT * FROM appr_order_delay_code WHERE order_id='" . $orderId . "' AND start_date > 0 AND end_date > 0";
        $rows = \DB::select($sql);
        if ($rows) {
            foreach ($rows as $row) {
                $diff = $this->dateDiffHours(date('Y-m-d H:i', $row->start_date), date('Y-m-d H:i', $row->end_date));
                $items[] = array(
                    'start' => $row->start_date,
                    'end' => $row->end_date,
                    'start_human' => date('m/d/Y H:i:s', $row->start_date),
                    'end_human' => date('m/d/Y H:i:s', $row->end_date),
                    'diff_human' => $diff,
                    'diff' => $this->getOrderTurnTimeInMinutesByTurnTimeString($diff),
                    'note' => $row->note,
                );
            }
        }

        return $items;
    }
}