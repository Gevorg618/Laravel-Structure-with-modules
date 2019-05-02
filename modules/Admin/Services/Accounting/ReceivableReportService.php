<?php

namespace Modules\Admin\Services\Accounting;


use App\Models\Clients\Client;
use Modules\Admin\Helpers\DateHelper;

class ReceivableReportService
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            '60' => '< 60',
            '60-90' => '60-90',
            '90-120' => '90-120',
            '120' => '120+',
            'past' => 'Past Due'
        ];
    }

    /**
     * @return array
     */
    public function getCredits()
    {
        return [
            'y' => 'Yes',
            'n' => 'No'
        ];
    }

    /**
     * @param $filter
     * @param $credits
     * @return array
     */
    public function getInvoiced($filter, $credits)
    {
        $statuses = [6, 10, 14, 16, 17, 18, 20];
        $clients = Client::with('orders')->invoiced()
            ->whereHas('orders', function ($query) use ($statuses) {
                return $query->whereIn('status', $statuses)
                    ->whereColumn('paid_amount', '<>', 'invoicedue');
            })
            ->latest('id')->paginate(10);
        return [
            $this->accountingGetClientsData($clients, [], true),
            $clients
        ];
    }

    /**
     * @param $filter
     * @param $credits
     * @return array
     */
    public function getNonInvoiced($filter, $credits)
    {
        $statuses = [6, 10, 14, 16, 17, 18, 20];
        $clients = Client::with('orders')->nonInvoiced()
            ->whereHas('orders', function ($query) use ($statuses) {
                return $query->whereIn('status', $statuses)
                    ->whereColumn('paid_amount', '<>', 'invoicedue');
            })
            ->latest('id')->paginate(10);
        return [
            $this->accountingGetClientsData($clients, [], true, 'noninvoiced'),
            $clients
        ];
    }

    /**
     * @return array
     */
    protected function initCountsData()
    {
        return [
            "count" => 0,
            "invoice" => 0,
            "paid" => 0,
            "split" => 0,
            "margin" => 0,
            "due" => 0,
        ];
    }

    /**
     * @return array
     */
    protected function initCreditsData()
    {
        return [
            "count" => 0,
            "due" => 0,
        ];
    }

    /**
     * @param $clients
     * @param array $filters
     * @param bool $addNew
     * @param string $invoicedKey
     * @return array
     */
    protected function accountingGetClientsData($clients, $filters = [], $addNew = false, $invoicedKey = 'invoiced')
    {

        $items = [
            'rows' => [],
            'counts' => [
                '60' => $this->initCountsData(),
                '60-90' => $this->initCountsData(),
                '90-120' => $this->initCountsData(),
                '120' => $this->initCountsData(),
                'past' => $this->initCountsData(),
                "total" => $this->initCountsData(),
                "avgmargin" => $this->initCountsData(),
                "credits" => $this->initCreditsData(),
            ]
        ];
        $newItems = [
            'invoiced' => [
                'rows' => [],
                'counts' => [
                    '60' => $this->initCountsData(),
                    '60-90' => $this->initCountsData(),
                    '90-120' => $this->initCountsData(),
                    '120' => $this->initCountsData(),
                    'past' => $this->initCountsData(),
                    "total" => $this->initCountsData(),
                    "avgmargin" => $this->initCountsData(),
                    "credits" => $this->initCreditsData(),
                ]
            ],
            'noninvoiced' => [
                'rows' => [],
                'counts' => [
                    '60' => $this->initCountsData(),
                    '60-90' => $this->initCountsData(),
                    '90-120' => $this->initCountsData(),
                    '120' => $this->initCountsData(),
                    'past' => $this->initCountsData(),
                    "total" => $this->initCountsData(),
                    "avgmargin" => $this->initCountsData(),
                    "credits" => $this->initCreditsData(),
                ]
            ]
        ];

        foreach ($clients as $client) {
            $rows = $this->accountingGetClientInfo($client, $filters);

            if ($rows === false) {
                continue;
            }

            $items['rows'][$client->id] = $rows;
            $newItems[$invoicedKey]['rows'][$client->id] = $rows;

            if ($rows && count($rows) && count($rows['counts'])) {
                foreach ($rows['counts'] as $type => $count) {
                    foreach ($count as $k => $v) {
                        $items['counts'][$type][$k] += $v;
                        $newItems[$invoicedKey]['counts'][$type][$k] += $v;
                    }
                }
                // Set AVG Margin
                $items['counts']['avgmargin']['margin'] = ($items['counts']['total']['margin'] / $items['counts']['avgmargin']['count']);
                $newItems[$invoicedKey]['counts']['avgmargin']['margin'] = ($items['counts']['total']['margin'] / $items['counts']['avgmargin']['count']);
            }
        }

        if ($addNew) {
            return $newItems;
        }

        return $items;
    }

    /**
     * @param $groupData
     * @param $filters
     * @return array|bool
     */
    protected function accountingGetClientInfo($groupData, $filters)
    {
        // Get orders
        $orders = $this->accountingGetClientOrders($groupData, $filters);

        // Build counts
        $counts = $this->accountingGetOrderCounts($orders);

        // Make sure the total > 0
        if ($counts['total']['count'] <= 0) {
            return false;
        }

        $info = [
            'data' => $groupData,
            'orders' => $orders,
            'counts' => $counts,
        ];

        return $info;
    }

    /**
     * @param $groupData
     * @param array $filters
     * @return array
     */
    protected function accountingGetClientOrders($groupData, $filters = array())
    {
        $orders = $groupData->orders;
        $items = [];
        if ($orders && count($orders)) {
            //$timer->setMarker(__CLASS__ . '_ORDERS'.$groupId.'_START');
            foreach ($orders as $id => $order) {
                $date = null;
                $dateType = null;
                //$qcApproved = getOrderDateDeliveredTimeStamp($order->id);
                if ($order->date_delivered) {
                    $date = strtotime($order->date_delivered);
                    $dateType = 'QC Approved Date';
                } elseif ($order->completed) {
                    $date = strtotime($order->completed);
                    $dateType = 'Completed Date';
                } elseif ($order->submitted) {
                    $date = strtotime($order->submitted);
                    $dateType = 'Submitted Date';
                } else {
                    $date = strtotime($order->ordereddate);
                    $dateType = 'Ordered Date';
                }

                $order->completed_date = $date;
                $order->completed_date_human = $date ? date('m/d/Y', $date) : '';
                $order->completed_date_type = $dateType;

                $order->amount_due = ($order->invoicedue - $order->paid_amount);

                $days = $this->accountingGetOrderTimeInvoiced(time(), $date);
                $order->days_invoiced = $days;
                $order->days_group = $this->accountingGetGroupedDays($days);

                // Filter data
                if (count($filters)) {
                    // Show credits?
                    if (isset($filters['credits'])) {
                        if ($filters['credits'] == 'n') {
                            // Hide if credit
                            if ($order->paid_amount > $order->invoicedue) {
                                continue;
                            }
                        }
                    }
                    // Do we need to show only certain orders by days
                    if (isset($filters['days'])) {
                        if (!$this->accountingGetfilteredDays($filters['days'], $days)) {
                            continue;
                        }
                    }

                    if (isset($filters['days-group'])) {
                        if (!$this->accountingGetfilteredDaysGroup($filters['days-group'], $days, $order->days_group)) {
                            continue;
                        }
                    }

                    // Do we want to ignore the order
                    if (isset($filters['ignore']) && count($filters['ignore'])) {
                        if (in_array($order->id, $filters['ignore'])) {
                            continue;
                        }
                    }
                }

                // Add to items
                $items[$id] = $order;
            }
            //$timer->setMarker(__CLASS__ . '_ORDERS'.$groupId.'_END');
        }

        /*uasort($items, function ($a, $b) {
            return $a->completed_date - $b->completed_date;
        });*/

        return $items;
    }

    /**
     * @param $from
     * @param $to
     * @return float|string
     */
    protected function accountingGetOrderTimeInvoiced($from, $to)
    {
        $obj = DateHelper::dateDiffObj(date('Y-m-d H:i:s', $from), date('Y-m-d H:i:s', $to));

        // Set days
        $int = $obj->format('%d');

        // Covnert months to days
        $int += floor($obj->format('%m') * 30);

        // Convert years to days
        $int += floor($obj->format('%y') * 365);

        return $int;
    }

    /**
     * @param $days
     * @return int|string
     */
    protected function accountingGetGroupedDays($days)
    {
        if ($days > 120) {
            return 120;
        }
        if ($days > 90 && $days <= 120) {
            return '90-120';
        }
        if ($days > 60 && $days <= 90) {
            return '60-90';
        }
        return 60;
    }

    /**
     * @param $types
     * @param $days
     * @return bool
     */
    protected function accountingGetfilteredDays($types, $days)
    {
        foreach ($types as $type) {
            if ($type == 'past') {
                if ($days > 60) {
                    return true;
                }
            } elseif ($type == 'total') {
                return true;
            } else {
                if ((string)$type == (string)$days) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param $types
     * @param $days
     * @param $group
     * @return bool
     */
    protected function accountingGetfilteredDaysGroup($types, $days, $group)
    {
        foreach ($types as $type) {
            if ($type == 'past') {
                if ($days > 60) {
                    return true;
                }
            } elseif ($type == 'total') {
                return true;
            } else {
                if ((string)$type == (string)$group) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param $orders
     * @return array
     */
    protected function accountingGetOrderCounts($orders)
    {
        $totals = [
            '60' => $this->initCountsData(),
            '60-90' => $this->initCountsData(),
            '90-120' => $this->initCountsData(),
            '120' => $this->initCountsData(),
            'past' => $this->initCountsData(),
            'total' => $this->initCountsData(),
            'avgmargin' => $this->initCountsData(),
            'credits' => $this->initCreditsData(),
        ];

        if ($orders && count($orders)) {
            foreach ($orders as $order) {
                // Init
                $days = $order->days_invoiced;
                $invoice = $order->invoicedue;
                $split = $order->split_amount;
                $paid = $order->paid_amount;
                $margin = ($order->invoicedue - $order->split_amount);
                $due = ($order->invoicedue - $order->paid_amount);

                if ($due < 0) {
                    $totals['credits']['count'] += 1;
                    $totals['credits']['due'] += $due;
                } else {
                    // Add totals to the correct values
                    if ($days > 120) {
                        // 120
                        $totals['120']['count'] += 1;
                        $totals['120']['invoice'] += $invoice;
                        $totals['120']['paid'] += $paid;
                        $totals['120']['split'] += $split;
                        $totals['120']['margin'] += $margin;
                        $totals['120']['due'] += $due;
                    } elseif ($days > 90 && $days <= 120) {
                        // 90 -120
                        $totals['90-120']['count'] += 1;
                        $totals['90-120']['invoice'] += $invoice;
                        $totals['90-120']['paid'] += $paid;
                        $totals['90-120']['split'] += $split;
                        $totals['90-120']['margin'] += $margin;
                        $totals['90-120']['due'] += $due;
                    } elseif ($days > 60 && $days <= 90) {
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

                    if ($days > 60) {
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

    /**
     * @param $clients
     * @param array $filters
     * @return array
     */
    public function accountingViewClientsGetInfo($clients, $filters = [])
    {
        $result = Client::whereIn('id', $clients)->get();
        $items = [];
        if ($result) {
            $items = $this->accountingGetClientsData($result, $filters);
        }

        return $items;
    }

    /**
     * @param $ids
     * @return static
     */
    public function getPDFClientLabels($ids)
    {
        $labels = [];

        if ($ids) {
            $clients = Client::whereIn('id', $ids)->get();
            foreach ($clients as $row) {
                $name = wordwrap($row->descrip, 33, "<br>");
                $address = ucwords(strtolower(trim($row->address1 . ' ' . $row->address2)));
                $address2 = ucwords(trim(trim($row->city . ', ' . strtoupper($row->state) . ' ' . $row->zip), ','));
                // AP Override
                if ($row->ap_contact) {
                    if ($row->ap_contact && $row->ap_company) {
                        $name = wordwrap($row->ap_contact, 33, "<br>");
                        $name .= "\n";
                        $name .= wordwrap($row->ap_company, 33, "<br>");
                    } else {
                        if ($row->ap_company) {
                            $name = wordwrap($row->ap_company, 33, "<br>");
                        } elseif ($row->ap_contact) {
                            $name = wordwrap($row->ap_contact, 33, "<br>");

                            if ($row->ap_company) {
                                $name .= "\n";
                                $name .= wordwrap($row->ap_company, 33, "<br>");
                            } elseif ($row->descrip) {
                                $name .= "\n";
                                $name .= wordwrap($row->descrip, 33, "<br>");
                            }
                        }
                    }

                }

                if ($row->ap_address1) {
                    $address = ucwords(strtolower(trim($row->ap_address1 . ' ' . $row->ap_address2)));
                }

                if ($row->ap_city) {
                    $address2 = ucwords(trim(trim($row->ap_city . ', ' . strtoupper($row->ap_state) . ' ' . $row->ap_zip), ','));
                }

                $labels[] = sprintf("%s<br>%s<br>%s", $name, $address, $address2);
            }
        }
        return collect($labels)->chunk(2);
    }
}