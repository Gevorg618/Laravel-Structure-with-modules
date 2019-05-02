<?php

namespace Modules\Admin\Repositories\Statistics;
use Modules\Admin\Repositories\Ticket\OrderRepository;
use Modules\Admin\Repositories\Customizations\TypesRepository;
use Yajra\DataTables\Datatables;

class AccountingBigStatisticsRepository
{

    private $settingKey = 'stats_settings_appr_types';

    /**
     * Object of OrderRepository class
     *
     * @var orderRepo
     */
    private $orderRepo;

    /**
     * Object of TypesRepository class
     *
     * @var typeRepo
     */
    private $typeRepo;

    /**
     * StatisticsRepository constructor.
     *
     */
    public function __construct()
    {
        $this->orderRepo = new OrderRepository();
        $this->typeRepo = new TypesRepository();
    }

    /**
     * get  statistics more info
     *
     * @param date $fromDate
     * @param date $toDate
     * @param array $clients
     * 
     * @return response 
     */
    public function statistics()
    {

       $fromDate = sprintf("%s 00:00:00", date('Y-m-d'));
       $toDate = sprintf("%s 23:59:59", date('Y-m-d'));

       $placedOrders  =   $this->getCreatedOrders($fromDate, $toDate);
       $completedOrders = $this->getCompletedOrders($fromDate, $toDate);
       $assignedOrders = $this->getAssignedOrders($fromDate, $toDate);
       $canceledOrders = $this->getCanceledOrders($fromDate, $toDate);
       $qcOrders = $this->getQcOrders($fromDate, $toDate);
       $monthlyRevenue = $this->getMonthlyRevenue();

       // $outstandingAccounts = $this->getOutstandingAccounts();

       $dailyMargin = $this->getDailyMargin();
       $deliveredInvoiceTotal = $this->orderRepo->getAccountingStatsDeliveredInvoiced($fromDate, $toDate, $this->settingKey, 'invoicedue');
       $deliveredPaidTotal = $this->orderRepo->getAccountingStatsDeliveredInvoiced($fromDate, $toDate, $this->settingKey, 'paid_amount');
       $totalSplit = $this->orderRepo->getAccountingStatsAmountbyDate($fromDate, $toDate, $this->settingKey, 'split_amount');
       $totalMargin = $deliveredInvoiceTotal - $totalSplit;
       $percentCollected = $deliveredInvoiceTotal ? ($deliveredPaidTotal*100/$deliveredInvoiceTotal) : 0;

       $accountingStatsDeliveredCC  = $this->accountingStatsDeliveredCC($fromDate, $toDate);
       $deliveredCreditCardPercent = $completedOrders ? ($accountingStatsDeliveredCC*100/$completedOrders) : 0;

       $accountingStatsDeliveredCheck  = $this->accountingStatsDeliveredCheck($fromDate, $toDate);
       $deliveredCheckPercent = $completedOrders ? ($accountingStatsDeliveredCheck*100/$completedOrders) : 0;

       $accountingStatsDeliveredInvoice = $this->accountingStatsDeliveredInvoice($fromDate, $toDate);
       $deliveredInvoicePercent = $completedOrders ? ($accountingStatsDeliveredInvoice*100/$completedOrders) : 0;
       
       $accountingStatsDeliveredCOD = $this->accountingStatsDeliveredCOD($fromDate, $toDate);
       $deliveredCODPercent = $completedOrders ? ($accountingStatsDeliveredCOD*100/$completedOrders) : 0;

       $response = [
            'placedOrder' => $placedOrders,
            'completedOrder' => $completedOrders,
            'assignedOrders' => $assignedOrders,
            'canceledOrders' => $canceledOrders,
            'qcOrders'  => $qcOrders,
            'monthlyRevenue' => $monthlyRevenue,
            // 'outstandingAccounts' => $outstandingAccounts
            'dailyMargin' => $dailyMargin,
            'deliveredInvoiceTotal' => $deliveredInvoiceTotal,
            'deliveredPaidTotal' => $deliveredPaidTotal,
            'totalMargin' => $totalMargin,
            'percentCollected' => $percentCollected, 
            'accountingStatsDeliveredCC' => $accountingStatsDeliveredCC, 
            'deliveredCreditCardPercent' => $deliveredCreditCardPercent,
            'accountingStatsDeliveredCheck' => $accountingStatsDeliveredCheck,
            'deliveredCheckPercent' => $deliveredCheckPercent,
            'accountingStatsDeliveredInvoice' => $accountingStatsDeliveredInvoice,
            'deliveredInvoicePercent' => $deliveredInvoicePercent,
            'accountingStatsDeliveredCOD' => $accountingStatsDeliveredCOD,
            'deliveredCODPercent' => $deliveredCODPercent

       ];
       
       return $response;
    }

    /**
     * get created orders
     *
     * @return array collection
     */
    public function getCreatedOrders($fromDate, $toDate)
    {

        $createdOrders = $this->orderRepo->getStatsCreatedOrders($fromDate, $toDate);

        return $createdOrders->count();
    }

    /**
     * get completed orders
     *
     * @return array collection
     */
    public function getCompletedOrders($fromDate, $toDate)
    {

        $completedOrders = $this->orderRepo->getStatsCompletedOrders($fromDate, $toDate);

        return $completedOrders->count();
    }

    /**
     * get assigned orders
     *
     * @return array collection
     */
    public function getAssignedOrders($fromDate, $toDate)
    {

        $assignedOrders = $this->orderRepo->getStatsAssignedOrders($fromDate, $toDate);

        return $assignedOrders->count();
    }

    /**
     * get canceled orders
     *
     * @return array collection
     */
    public function getCanceledOrders($fromDate, $toDate)
    {

        $canceledOrders = $this->orderRepo->getStatsOrdersCanceled($fromDate, $toDate);

        return $canceledOrders->count();
    }

    /**
     * get canceled orders
     *
     * @return array collection
     */
    public function getQcOrders($fromDate, $toDate)
    {

        $qcOrders = $this->orderRepo->getQcOrders($this->settingKey);
        
        return $qcOrders->count();
    }

    /**
     * get accountingStatsDeliveredCC
     *
     * @return array collection
     */
    public function accountingStatsDeliveredCC($fromDate, $toDate)
    {
        return $this->orderRepo->getAccountingStatsDeliveredCC($fromDate, $toDate, $this->settingKey);
    }

    /**
     * get accountingStatsDeliveredCCPaid
     *
     * @return array collection
     */
    public function accountingStatsDeliveredCheck($fromDate, $toDate)
    {
        return $this->orderRepo->getAccountingStatsDeliveredCheck($fromDate, $toDate, $this->settingKey);
    }

    /**
     * get accountingStatsDeliveredCC
     *
     * @return array collection
     */
    public function accountingStatsDeliveredInvoice($fromDate, $toDate)
    {
        return $this->orderRepo->getAccountingStatsDeliveredInvoice($fromDate, $toDate, $this->settingKey);
    }

    /**
     * get accountingStatsDeliveredCOD
     *
     * @return array collection
     */
    public function accountingStatsDeliveredCOD($fromDate, $toDate)
    {
        return $this->orderRepo->getAccountingStatsDeliveredCOD($fromDate, $toDate, $this->settingKey);
    }

    /**
     * get Monthly Revenue 
     *
     * @return array collection
     */
    public function getMonthlyRevenue()
    {
        $months = $this->months();

        $data = [];

        foreach($months as $m) {
            $monthFrom = $m['from'];
            $monthTo = $m['to'];
            

            $totalInvoices = $this->orderRepo->getAccountingStatsAmountbyDate($monthFrom, $monthTo, $this->settingKey, 'invoicedue');
        
            $totalPaid = $this->orderRepo->getAccountingStatsAmountbyDate($monthFrom, $monthTo, $this->settingKey, 'paid_amount');
            $totalSplit = $this->orderRepo->getAccountingStatsAmountbyDate($monthFrom, $monthTo, $this->settingKey, 'split_amount');
            $totalMargin = $totalInvoices - $totalSplit;

            $data[ $m['date'] ] = array(
                'invoice' => floatval($totalInvoices),
                'paid' => floatval($totalPaid),
                'split' => floatval($totalSplit),
                'margin' => floatval($totalMargin),
            );
        }

        $jsCode = [];

        foreach($data as $d => $v) {
            $jsCode[] = [
                'y' => $d, 
                'a'        => $v['invoice'], 
                'b'        => $v['paid'], 
                'c'        => $v['split'], 
                'd'        => $v['margin'],
            ];
    
        }

        return $jsCode;
    
    }

    /**
     * get DailyMargin
     *
     * @return array collection
     */
    public function getDailyMargin()
    {

        $days = $this->monthsDays();

        $data = [];

        foreach($days as $day) {

            $dayFrom = strtotime($day['from']);
            $dayTo = strtotime($day['to']);

            $invoice = $this->orderRepo->getAccountingStatsDeliveredInvoiced($dayFrom, $dayTo, $this->settingKey);
            $split = $totalSplit = $this->orderRepo->getAccountingStatsAmountbyDate($dayFrom, $dayTo, $this->settingKey, 'split_amount');
            $margin = $invoice - $split;

            $data[ $day['date'] ] = array(
                'margin' => floatval($margin),
                'invoice' => floatval($invoice),
                'split' => floatval($split),
            );
        }

        $marginCode = [];
        foreach ($data as $d => $v) {
            $marginCode[] =  [
                    'y' => $d, 
                    'a'        => $v['margin'], 
                    'b'        => $v['invoice'], 
                    'c'        => $v['split']
                ];
        }
        return $marginCode;
    }

    /**
     * get Outstanding Accounts
     *
     * @return array collection
     */
    public function getOutstandingAccounts()
    {
        $this->orderRepo->accountingGetClientOrders();
    }

    /**
     * months days
     *
     * @return array 
     */
    public function monthsDays()
    {
        // Daily
        $numDays = 31;
        $days = [];

        // Current Month
        for($i=1; $i<=$numDays; $i++) {
            $date = sprintf('%s-%s-%s', date('Y'), date('m'), $i);
            $time = strtotime($date);
            $days[] = array(
                'from' => date('Y-m-d 00:00:00', $time),
                'to' => date('Y-m-d 23:59:59', $time),
                'date' => date('Y-m-d', $time),
            );
        }

        return $days;
    }

    /**
     * motns
     *
     * @return array 
     */
    public function months()
    {
        $numMonths = 13;
        $months = array();

        // Current Month
        $time = time();
        $months[] = array(
            'from' => date('Y-m-d 00:00:00', strtotime('first day of this month', $time)),
            'to' => date('Y-m-d 23:59:59', strtotime('last day of this month', $time)),
            'date' => date('Y-m', $time),
        );

        $lastDateUsed = date('Y-m-d');

        for($i=1; $i<=$numMonths; $i++) {
            $first = new \DateTime( $lastDateUsed );
            $last = new \DateTime( $lastDateUsed );
            $prev = new \DateTime( $lastDateUsed );

            $firstDay = $first->modify( 'first day of this month' );
            $lastDay = $last->modify( 'last day of this month' );
            $previous = $prev->modify( 'last day of previous month' );

            $lastDateUsed = $previous->format('Y-m-d');
            $months[] = array(
                'from' => $firstDay->format('Y-m-d 00:00:00'),
                'to' => $lastDay->format('Y-m-d 23:59:59'),
                'date' => $firstDay->format('Y-m'),
            );
        }

        unset($months[0]);

        return $months;
    }


    /**
     * Calculate the number of days difference from completed date till now
     * @param int $from
     * @param int $to
     * @return int
     */
    function accountingGetOrderTimeInvoiced($from, $to) {
        $obj = dateDiffObj(date('Y-m-d H:i:s', $from), date('Y-m-d H:i:s', $to));
        
        // Set days
        $int = $obj->format('%d');
        
        // Covnert months to days
        $int += floor($obj->format('%m') * 30);
        
        // Convert years to days
        $int += floor($obj->format('%y') * 365);
        
        return $int;
    }

    /**
     * Return string representation of the grouped by days
     * @param int $days
     * @return string
     */
    function accountingGetGroupedDays($days) {
        if($days > 120) {
            return 120;
        } elseif($days > 90 && $days <= 120) {
            return '90-120';
        } elseif($days > 60 && $days <= 90) {
            return '60-90';
        } else {
            return 60;
        }
    }

    /**
     * Calculate totals and counts by the array of orders passed
     * @param array $orders
     * @return array
     */
    function accountingGetOrderCounts($orders) {
        $totals = array(
            '60' => array(
                'count' => 0,
                'invoice' => 0,
                'paid' => 0,
                'split' => 0,
                'margin' => 0,
                'due' => 0,
            ),
            '60-90' => array(
                'count' => 0,
                'invoice' => 0,
                'paid' => 0,
                'split' => 0,
                'margin' => 0,
                'due' => 0,
            ),
            '90-120' => array(
                'count' => 0,
                'invoice' => 0,
                'paid' => 0,
                'split' => 0,
                'margin' => 0,
                'due' => 0,
            ),
            '120' => array(
                'count' => 0,
                'invoice' => 0,
                'paid' => 0,
                'split' => 0,
                'margin' => 0,
                'due' => 0,
            ),
            'past' => array(
                'count' => 0,
                'invoice' => 0,
                'paid' => 0,
                'split' => 0,
                'margin' => 0,
                'due' => 0,
            ),
            'total' => array(
                'count' => 0,
                'invoice' => 0,
                'paid' => 0,
                'split' => 0,
                'margin' => 0,
                'due' => 0,
            ),
            'avgmargin' => array(
                'count' => 0,
                'invoice' => 0,
                'paid' => 0,
                'split' => 0,
                'margin' => 0,
                'due' => 0,
            ),
            'credits' => array(
                'count' => 0,
                'due' => 0,
            ),
        );
        
        if($orders && count($orders)) {
            foreach($orders as $order) {
                // Init
                $days = $order->days_invoiced;
                $invoice = $order->invoicedue;
                $split = $order->split_amount;
                $paid = $order->paid_amount;
                $margin = ($order->invoicedue - $order->split_amount);
                $due = ($order->invoicedue - $order->paid_amount);

                if($due < 0) {
                    $totals['credits']['count'] += 1;
                    $totals['credits']['due'] += $due;
                } else {
                    // Add totals to the correct values
                    if($days > 120) {
                        // 120
                        $totals['120']['count'] += 1;
                        $totals['120']['invoice'] += $invoice;
                        $totals['120']['paid'] += $paid;
                        $totals['120']['split'] += $split;
                        $totals['120']['margin'] += $margin;
                        $totals['120']['due'] += $due;
                    } elseif($days > 90 && $days <= 120) {
                        // 90 -120
                        $totals['90-120']['count'] += 1;
                        $totals['90-120']['invoice'] += $invoice;
                        $totals['90-120']['paid'] += $paid;
                        $totals['90-120']['split'] += $split;
                        $totals['90-120']['margin'] += $margin;
                        $totals['90-120']['due'] += $due;
                    } elseif($days > 60 && $days <= 90) {
                        // 60 - 90
                        $totals['60-90']['count'] += 1;
                        $totals['60-90']['invoice'] += $invoice;
                        $totals['60-90']['paid'] += $paid;
                        $totals['60-90']['split'] += $split;
                        $totals['60-90']['margin'] += $margin;
                        $totals['60-90']['due'] += $due;
                    } else {
                        // 60
                        $totals['60']['count'] += 1;
                        $totals['60']['invoice'] += $invoice;
                        $totals['60']['paid'] += $paid;
                        $totals['60']['split'] += $split;
                        $totals['60']['margin'] += $margin;
                        $totals['60']['due'] += $due;
                    }

                    if($days > 60) {
                        // Past Due
                        $totals['past']['count'] += 1;
                        $totals['past']['invoice'] += $invoice;
                        $totals['past']['paid'] += $paid;
                        $totals['past']['split'] += $split;
                        $totals['past']['margin'] += $margin;
                        $totals['past']['due'] += $due;
                    }
                }

                // Total
                $totals['total']['count'] += 1;
                $totals['total']['invoice'] += $invoice;
                $totals['total']['paid'] += $paid;
                $totals['total']['split'] += $split;
                $totals['total']['margin'] += $margin;
                $totals['total']['due'] += $due;
            }
        }
        
        // Total AVG Margin
        $totals['avgmargin']['count'] = $totals['total']['count'];
        $totals['avgmargin']['margin'] = $totals['total']['count'] ? ($totals['total']['margin'] / $totals['total']['count']) : 0;
        
        return $totals;
    }
}
