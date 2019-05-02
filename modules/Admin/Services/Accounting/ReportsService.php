<?php

namespace Modules\Admin\Services\Accounting;


use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\Repositories\Appraisal\FdPaymentRepository;
use Modules\Admin\Repositories\Accounting\AppraiserPaymentRepository;
use Modules\Admin\Repositories\Accounting\CimCheckPaymentRepository;
use Modules\Admin\Repositories\Ticket\OrderRepository;

class ReportsService
{
    const DELIVERY_SALES = 'delivery_sales';
    const DELIVERY_COS = 'delivery_cos';
    const CREDIT_CARD_CHECK_RECEIPTS = 'credit_card_check_receipts';
    const CHECK_PAYMENTS = 'check_payments';
    const REFUNDS = 'refunds';
    const PARTIAL_PAID_ORDERS = 'partial_paid_orders';
    const PAYMENTS_COLLECTED = 'payments_collected';

    protected $orderRepository;
    protected $fdPaymentRepository;
    protected $cimCheckPaymentRepository;
    protected $appraiserPaymentRepository;

    protected $rows = [];
    protected $headers = [];

    /**
     * AccountingReportsService constructor.
     * @param $orderRepository
     */
    public function __construct(
        OrderRepository $orderRepository,
        FdPaymentRepository $fdPaymentRepository,
        CimCheckPaymentRepository $cimCheckPaymentRepository,
        AppraiserPaymentRepository $appraiserPaymentRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->fdPaymentRepository = $fdPaymentRepository;
        $this->cimCheckPaymentRepository = $cimCheckPaymentRepository;
        $this->appraiserPaymentRepository = $appraiserPaymentRepository;
    }

    /**
     * @return array
     */
    public function getDateTypes()
    {
        return [
            'ordereddate' => 'Date Ordered',
            'date_delivered' => 'Date Delivered',
            'fd.created_date' => 'Payment Received Date'
        ];
    }

    /**
     * @return array
     */
    public function getReportList()
    {
        return [
            self::DELIVERY_SALES => 'Delivery - Sales',
            self::DELIVERY_COS => 'Delivery - COS',
            self::CREDIT_CARD_CHECK_RECEIPTS => 'Credit Card / Check Receipts',
            self::CHECK_PAYMENTS => 'Check Payments',
            self::REFUNDS => 'Refunds',
            self::PARTIAL_PAID_ORDERS => 'Partial Paid Orders',
            self::PAYMENTS_COLLECTED => 'Payments Collected',
        ];
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @return array
     */
    public function getReportDeliverySales($dateFrom, $dateTo, $dateType)
    {
        $full = $this->orderRepository->getFullForDeliverySalesReport($dateFrom, $dateTo, $dateType);
        $balance = $this->orderRepository->getBalanceForDeliverySalesReport($dateFrom, $dateTo, $dateType);
        $unpaid = $this->orderRepository->getUnpaidForDeliverySalesReport($dateFrom, $dateTo, $dateType);
        $paid = $this->orderRepository->getPaidForDeliverySalesReport($dateFrom, $dateTo, $dateType);
        $overall = $this->orderRepository->getOverallForDeliverySalesReport($dateFrom, $dateTo, $dateType);

        $balancePaidFull = $this->orderRepository->getBalancePaidFullForDeliverySalesReport($dateFrom, $dateTo, $dateType);
        $balancePaid = $this->orderRepository->getBalancePaidForDeliverySalesReport($dateFrom, $dateTo, $dateType);

        return [
            'totalOrdersPaidInFullCount' => $full->total_orders ?? 0,
            'totalOrdersPaidInFullAmount' => $full->total_amount ?? 0,
            'totalOrdersBalanceDueCount' => $balance->total_orders ?? 0,
            'totalOrdersBalanceDueAmount' => $balance->total_amount ?? 0,

            'totalOrdersBalancePaidFullCount' => $balancePaidFull->total_orders ?? 0,
            'totalOrdersBalancePaidFullAmount' => $balancePaidFull->total_amount ?? 0,
            'totalOrdersBalancePaidCount' => $balancePaid->total_orders ?? 0,
            'totalOrdersBalancePaidAmount' => $balancePaid->total_amount ?? 0,

            'totalOrdersUnpaidCount' => $unpaid->total_orders ?? 0,
            'totalOrdersUnpaidAmount' => $unpaid->total_amount ?? 0,
            'totalOrdersPaidCount' => $paid->total_orders ?? 0,
            'totalOrdersPaidAmount' => $paid->total_amount ?? 0,
            'totalOverallOrdersCount' => $overall->total_orders ?? 0,
            'totalOverallOrdersAmount' => $overall->total_amount ?? 0,
        ];
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @return array
     */
    public function getReportDeliveryCOS($dateFrom, $dateTo, $dateType)
    {
        // Build
        $paid = $this->orderRepository->getPaidForDeliveryCosReport($dateFrom, $dateTo, $dateType);
        $unpaid = $this->orderRepository->getUnpaidForDeliveryCosReport($dateFrom, $dateTo, $dateType);
        $overall = $this->orderRepository->getOverallForDeliveryCosReport($dateFrom, $dateTo, $dateType);

        return [
            'totalOrdersAppraiserPaidCount' => $paid->total_orders ?? 0,
            'totalOrdersAppraiserPaidAmount' => $paid->total_amount ?? 0,
            'totalOrdersAppraiserUnPaidCount' => $unpaid->total_orders ?? 0,
            'totalOrdersAppraiserUnPaidAmount' => $unpaid->total_amount ?? 0,
            'totalOverallOrdersCount' => $overall->total_orders ?? 0,
            'totalOverallOrdersAmount' => $overall->total_amount ?? 0,
        ];
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @return array
     */
    public function getReportCreditCardAndChecksReceipts($dateFrom, $dateTo)
    {
        $creditCardsCompleted = $this->fdPaymentRepository->getCreditCardsCompletedForCreditCardReceiptReport($dateFrom, $dateTo);
        $creditCardsInProgress = $this->fdPaymentRepository->getCreditCardsInProgressForCreditCardReceiptReport($dateFrom, $dateTo);

        $checksCompleted = $this->fdPaymentRepository->getChecksCompletedForCreditCardReceiptReport($dateFrom, $dateTo);
        $checksInProgress = $this->fdPaymentRepository->getChecksInProgressForCreditCardReceiptReport($dateFrom, $dateTo);

        $creditCardsCompletedOne = $this->fdPaymentRepository->getCreditCardsCompletedOneForCreditCardReceiptReport($dateFrom, $dateTo);
        $checksCompletedOne = $this->cimCheckPaymentRepository->getChecksCompletedOneForCreditCardReceiptReport($dateFrom, $dateTo);

        $creditCardsCompletedMoreThanOne = $this->fdPaymentRepository->getCreditCardsCompletedMoreThanOneForCreditCardReceiptReport($dateFrom, $dateTo);
        $checksCompletedMoreThanOne = $this->cimCheckPaymentRepository->getChecksCompletedMoreThanOneForCreditCardReceiptReport($dateFrom, $dateTo);

        $creditCardsCompletedTotalOrders = $creditCardsCompleted->total_orders ?? 0;
        $creditCardsInProgressTotalOrders = $creditCardsInProgress->total_orders ?? 0;

        $creditCardsCompletedTotalAmount = $creditCardsCompleted->total_amount ?? 0;
        $creditCardsInProgressTotalAmount = $creditCardsInProgress->total_amount ?? 0;

        $checksCompletedTotalOrders = $checksCompleted->total_orders ?? 0;
        $checksInProgressTotalOrders = $checksInProgress->total_orders ?? 0;

        $checksCompletedTotalAmount = $checksCompleted->total_amount ?? 0;
        $checksInProgressTotalAmount = $checksInProgress->total_amount ?? 0;

        return [
            'creditCardsCompletedOne' => count($creditCardsCompletedOne),
            'checksCompletedOne' => count($checksCompletedOne),

            'creditCardsCompletedMoreThanOne' => $creditCardsCompletedMoreThanOne->total_orders ?? 0,
            'checksCompletedMoreThanOne' => count($checksCompletedMoreThanOne),

            'totalCreditCardCompletedOrders' => $creditCardsCompletedTotalOrders,
            'totalCreditCardCompletedAmount' => $creditCardsCompletedTotalAmount,
            'totalCreditCardInProgressOrders' => $creditCardsInProgressTotalOrders,
            'totalCreditCardInProgressAmount' => $creditCardsInProgressTotalAmount,

            'totalChecksCompletedOrders' => $checksCompleted->total_orders ?? 0,
            'totalChecksCompletedAmount' => $checksCompleted->total_amount ?? 0,
            'totalChecksInProgressOrders' => $checksInProgress->total_orders ?? 0,
            'totalChecksInProgressAmount' => $checksInProgress->total_amount ?? 0,

            'totalCreditCardOrdersCount' => $creditCardsCompletedTotalOrders + $creditCardsInProgressTotalOrders,
            'totalCreditCardOrdersAmount' => $creditCardsCompletedTotalAmount + $creditCardsInProgressTotalAmount,

            'totalChecksOrdersCount' => $checksCompletedTotalOrders + $checksInProgressTotalOrders,
            'totalChecksOrdersAmount' => $checksCompletedTotalAmount + $checksInProgressTotalAmount,

            'totalOrdersCount' => $creditCardsCompletedTotalOrders + $creditCardsInProgressTotalOrders + $checksCompletedTotalOrders + $checksInProgressTotalOrders,
            'totalOrdersAmount' => $creditCardsCompletedTotalAmount + $creditCardsInProgressTotalAmount + $checksCompletedTotalAmount + $checksInProgressTotalAmount,
        ];
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @return array
     */
    public function getReportCheckPayments($dateFrom, $dateTo)
    {
        // Build
        $complete = $this->orderRepository->getCompleteForCheckPaymentsReport($dateFrom, $dateTo);
        $inprogress = $this->orderRepository->getInProgressForCheckPaymentsReport($dateFrom, $dateTo);

        $totalAmount = $this->appraiserPaymentRepository->getTotalAmountForCheckPaymentsReport($dateFrom, $dateTo);

        // Completed amount
        $totalCompletedChecks = $this->appraiserPaymentRepository->getTotalCompletedChecksForCheckPaymentsReport($dateFrom, $dateTo);
        $totalCompletedAmount = $totalCompletedChecks->sum('amount');
        $totalCompletedCount = count($totalCompletedChecks);

        // In Progress amount
        $totalInProgressChecks = $this->appraiserPaymentRepository->getTotalInProgressChecksForCheckPaymentsReport($dateFrom, $dateTo);
        $totalInProgressAmount = $totalInProgressChecks->sum('amount');
        $totalInProgressCount = count($totalInProgressChecks);

        $completeTotalOrders = $complete->total_orders ?? 0;
        $completeTotalAmount = $complete->total_amount ?? 0;

        $inprogressTotalOrders = $inprogress->total_orders ?? 0;
        $inprogressTotalAmount = $inprogress->total_amount ?? 0;

        return [
            'totalCompletedOrdersCount' => $completeTotalOrders,
            'totalCompletedOrdersAmount' => $completeTotalAmount,
            'totalInProgressOrdersCount' => $inprogressTotalOrders,
            'totalInProgressOrdersAmount' => $inprogressTotalAmount,

            'totalPaymentsAmount' => $totalAmount,

            'totalPaymentsCompletedAmount' => $totalCompletedAmount,
            'totalPaymentsCompletedCount' => $totalCompletedCount,

            'totalPaymentsInProgressAmount' => $totalInProgressAmount,
            'totalPaymentsInProgressCount' => $totalInProgressCount,
        ];
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @return array
     */
    public function getReportRefunds($dateFrom, $dateTo)
    {
        // Build
        $creditCards = $this->fdPaymentRepository->getCreditCardsForRefundReport($dateFrom, $dateTo);
        $checks = $this->cimCheckPaymentRepository->getChecksForRefundReport($dateFrom, $dateTo);

        $creditCardsTotalOrders = $creditCards->total_orders ?? 0;
        $creditCardsTotalAmount = $creditCards->total_amount ?? 0;
        $checksTotalOrders = $checks->total_orders ?? 0;
        $checksTotalAmount = $checks->total_amount ?? 0;
        return [
            'totalCreditCardsRefundCount' => $creditCardsTotalOrders,
            'totalCreditCardsRefundAmount' => $creditCardsTotalAmount,
            'totalChecksRefundCount' => $checksTotalOrders,
            'totalChecksRefundAmount' => $checksTotalAmount,
        ];
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @return array
     */
    public function getReportPartialPaidOrders($dateFrom, $dateTo, $dateType)
    {
        $yesterdayOptions = [
            'dateType' => $dateType,
            'dateFrom' => date('1970-01-01 00:00:00', strtotime($dateFrom)),
            'dateTo' => date('Y-m-d 23:59:59', strtotime('-1 day', strtotime($dateTo)))
        ];
        $creditCardsInProgressYesterday = $this->fdPaymentRepository->getCreditCardsInProgressYesterday($yesterdayOptions);

        $creditCardsInProgress = $this->fdPaymentRepository->getCreditCardsInProgress($dateFrom, $dateTo);

        $deliveriesTodayPaidToday = $this->fdPaymentRepository->getDeliveriesTodayPaidToday($dateFrom, $dateTo, $dateType);

        $deliveriesTodayPaidPast = $this->fdPaymentRepository->getDeliveriesTodayPaidPast($dateFrom, $dateTo, $dateType);

        $currentOptions = [
            'dateType' => $dateType,
            'dateFrom' => date('1970-01-01 00:00:00', strtotime($dateFrom)),
            'dateTo' => $dateTo
        ];
        $creditCardsInProgressCurrent = $this->fdPaymentRepository->getCreditCardsInProgressCurrent($currentOptions);

        $creditCardsInProgressTotalOrders = $creditCardsInProgress->total_orders ?? 0;
        $creditCardsInProgressTotalAmount = $creditCardsInProgress->total_amount ?? 0;

        $creditCardsInProgressYesterdayTotalOrders = $creditCardsInProgressYesterday->total_orders ?? 0;
        $creditCardsInProgressYesterdayTotalAmount = $creditCardsInProgressYesterday->total_amount ?? 0;

        $creditCardsInProgressCurrentTotalOrders = $creditCardsInProgressCurrent->total_orders ?? 0;
        $creditCardsInProgressCurrentTotalAmount = $creditCardsInProgressCurrent->total_amount ?? 0;

        $deliveriesTodayPaidTodayTotalOrders = $deliveriesTodayPaidToday->total_orders ?? 0;
        $deliveriesTodayPaidTodayTotalAmount = $deliveriesTodayPaidToday->total_amount ?? 0;

        $deliveriesTodayPaidPastTotalOrders = $deliveriesTodayPaidPast->total_orders ?? 0;
        $deliveriesTodayPaidPastTotalAmount = $deliveriesTodayPaidPast->total_amount ?? 0;

        return [
            'creditCardsInProgressCount' => $creditCardsInProgressTotalOrders,
            'creditCardsInProgressAmount' => $creditCardsInProgressTotalAmount,

            'creditCardsInProgressYesterdayCount' => $creditCardsInProgressYesterdayTotalOrders,
            'creditCardsInProgressYesterdayAmount' => $creditCardsInProgressYesterdayTotalAmount,

            'creditCardsInProgressCurrentCount' => $creditCardsInProgressCurrentTotalOrders,
            'creditCardsInProgressCurrentAmount' => $creditCardsInProgressCurrentTotalAmount,

            'deliveriesTodayPaidTodayCount' => $deliveriesTodayPaidTodayTotalOrders,
            'deliveriesTodayPaidTodayAmount' => $deliveriesTodayPaidTodayTotalAmount,

            'deliveriesTodayPaidPastCount' => $deliveriesTodayPaidPastTotalOrders,
            'deliveriesTodayPaidPastAmount' => $deliveriesTodayPaidPastTotalAmount,
        ];
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @return array
     */
    public function getReportPaymentsCollected($dateFrom, $dateTo, $dateType)
    {
        $limit = 2000;
        $this->headers = [];
        $this->rows = [];

        $this->fdPaymentRepository->getCreditCardsForPaymentsCollectedReport($dateFrom, $dateTo, $dateType)
            ->chunk($limit, function (Collection $creditCards) {
                $this->paymentsCollectedCallback($creditCards);
            });

        $this->cimCheckPaymentRepository->getChecksForPaymentsCollectedReport($dateFrom, $dateTo, $dateType)
            ->chunk($limit, function (Collection $checks) {
                $this->paymentsCollectedCallback($checks);
            });

        return $this->rows;
    }

    /**
     * @param Collection $result
     */
    protected function paymentsCollectedCallback(Collection $result)
    {
        if (!count($result)) {
            return;
        }
        $resultArray = $result->toArray();

        if (!count($this->headers)) {
            $this->headers = array_keys(reset($resultArray));
        }

        $this->rows = array_merge($this->rows, $resultArray);
    }

    /**
     * @param $report
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @return array
     */
    public function getReportResult($report, $dateFrom, $dateTo, $dateType)
    {
        switch ($report) {
            case self::DELIVERY_SALES:
                return $this->getReportDeliverySales($dateFrom, $dateTo, $dateType);
            case self::DELIVERY_COS:
                return $this->getReportDeliveryCOS($dateFrom, $dateTo, $dateType);
            case self::CREDIT_CARD_CHECK_RECEIPTS:
                return $this->getReportCreditCardAndChecksReceipts($dateFrom, $dateTo);
            case self::CHECK_PAYMENTS:
                return $this->getReportCheckPayments($dateFrom, $dateTo);
            case self::REFUNDS:
                return $this->getReportRefunds($dateFrom, $dateTo);
            case self::PARTIAL_PAID_ORDERS:
                return $this->getReportPartialPaidOrders($dateFrom, $dateTo, $dateType);
            case self::PAYMENTS_COLLECTED:
                return $this->getReportPaymentsCollected($dateFrom, $dateTo, $dateType);
        }
    }
}
