<?php

namespace Modules\Admin\Services\Accounting;


use App\Models\AlternativeValuation\OrderStatus;
use Illuminate\Support\Collection;
use Modules\Admin\Repositories\Accounting\AgentPaymentRepository;
use Modules\Admin\Repositories\Accounting\AltOrderRepository;
use Modules\Admin\Repositories\Accounting\AltSubOrderRepository;

/**
 * Class AlPayableReportService
 * @package Modules\Admin\Services
 */
class AlPayableReportService
{
    protected $orderRepo;
    protected $subOrderRepo;
    protected $agentPaymentRepo;

    /**
     * AlPayableReportService constructor.
     * @param AltOrderRepository $orderRepo
     * @param AltSubOrderRepository $subOrderRepo
     * @param AgentPaymentRepository $agentPaymentRepo
     */
    public function __construct(
        AltOrderRepository $orderRepo,
        AltSubOrderRepository $subOrderRepo,
        AgentPaymentRepository $agentPaymentRepo
    )
    {
        $this->orderRepo = $orderRepo;
        $this->subOrderRepo = $subOrderRepo;
        $this->agentPaymentRepo = $agentPaymentRepo;
    }

    protected function setOrders($orders = [], Collection $collection)
    {
        $fullIds = $collection->pluck('id')->toArray();
        $agentAccepteds = $collection->pluck('acceptedby')->toArray();
        $isPaid = $this->agentPaymentRepo->getOrderAgentsCheckByOrdersAndAgentsId($fullIds, $agentAccepteds)
            ->groupBy('agentid');
        foreach ($collection as $row) {
            if (!$row->acceptedby) {
                continue;
            }

            // Did the appraiser get paid for this?


            if (isset($isPaid) && $isPaid->get($row->agent_accepted)) {
                continue;
            }

            $orders[] = $row;
        }
        return $orders;
    }

    /**
     * @param string $dateFrom
     * @param string $dateTo
     * @param array $states
     * @param array $clients
     * @return array
     */
    public function getData($dateFrom = '', $dateTo = '', $states = [], $clients = [])
    {
        $totalSum = 0;
        $totalBalance = 0;
        $full = $this->orderRepo->getAltOrdersForPayable(
            $dateFrom,
            $dateTo,
            $states,
            $clients
        );
        $subs = $this->subOrderRepo->getAltSubOrdersForPayable(
            $dateFrom,
            $dateTo,
            $states,
            $clients
        );
        $orders = [];
        if (count($full)) {
            $orders = $this->setOrders($orders, $full);
        }
        if (count($subs)) {
            $orders = $this->setOrders($orders, $subs);
        }
        $matches = [];
        // Group by agent
        foreach ($orders as $alrow) {
            $date = null;
            $dateType = null;
            if ($alrow->submitted) {
                $date = strtotime($alrow->submitted);
                $dateType = 'Submitted Date';
            } else {
                $date = strtotime($alrow->ordereddate);
                $dateType = 'Ordered Date';
            }

            // skip if older then the date to
            if ($dateTo && strtotime($dateTo . ' 23:59:59') < $date) {
                continue;
            }

            $alrow->completed_date = $date;
            $alrow->completed_date_human = $date ? date('m/d/Y', $date) : '';
            $alrow->completed_date_type = $dateType;

            if (!isset($matches[$alrow->acceptedby])) {
                $matches[$alrow->acceptedby] = [
                    'firstname' => optional($alrow->agentData)->firstname,
                    'lastname' => optional($alrow->agentData)->lastname,
                    'ein' => optional($alrow->agentData)->ein,
                    'id' => $alrow->acceptedby,
                    'company' => optional($alrow->agentData)->company,
                    'orders' => array(),
                    'sum_total' => 0,
                    'balance_total' => 0,
                ];
            }

            $alrow->amount_due = ($alrow->invoicedue - $alrow->paid_amount);
            $matches[$alrow->acceptedby]['orders'][] = $alrow;
            $matches[$alrow->acceptedby]['sum_total'] += $alrow->split_amount;
            $matches[$alrow->acceptedby]['balance_total'] += ($alrow->invoicedue - $alrow->paid_amount);

            $totalSum += $alrow->split_amount;
            $totalBalance += ($alrow->invoicedue - $alrow->paid_amount);
        }

        return [$matches, $totalSum, $totalBalance];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getALOrderStatuses()
    {
        return OrderStatus::pluck('name', 'id');
    }

    /**
     * @param array $rows
     * @return array
     */
    public function makeDataForExport($rows = [])
    {
        $statuses = $this->getALOrderStatuses();
        $items = [];
        foreach ($rows as $row) {
            if ($row['orders']) {
                foreach ($row['orders'] as $order) {
                    $items[] = [
                        'ID' => $order->id,
                        'Date Placed' => date('m/d/Y', strtotime($order->ordereddate)),
                        'Date Completed' => $order->completed_date_human,
                        'Address' => $order->address,
                        'Client' => $order->company,
                        'Status' => $statuses[$order->status] ?? 'N/A',
                        'Agent' => $row['firstname'] . ' ' . $row['lastname'],
                        'EIN' => $row['ein'],
                        'Company Name' => $row['company'],
                        'Amount' => $order->split_amount,
                        'Client Balance' => $order->invoicedue - $order->paid_amount,
                    ];
                }
            }
        }
        return $items;
    }
}
