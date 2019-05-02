<?php

namespace Modules\Admin\Repositories\Accounting;

use DB;
use Yajra\DataTables\Datatables;
use App\Models\DocuVault\Notification;
use App\Models\Management\WholesaleLenders\UserGroupLender as Lender;
use App\Models\Clients\Client;

class DocuVaultReceivablesRepository
{
    public $days;
    public $pastdue;
    public $ids;
    public static $multiTotal = 0;

    /**
     * Check if the id is a lender id
     * @return bool
     */
    public function isLender($id)
    {
        return strpos($id, 'l_')!==false;
    }

    /**
     * Check if the id is a group id
     * @return bool
     */
    public function isGroup($id)
    {
        return strpos($id, 'g_') !== false;
    }

    /**
     * clean id to get an integer
     * @return int
     */
    public function getCleanId($id)
    {
        return (int) str_replace(['g_', 'l_'], '', $id);
    }

    /**
     * Get appraisal lender orders
     *
     */
    protected function getAppraisalOrdersByLender($id)
    {
        return $this->getAppraisalOrdersQuery()
                    ->where('lender_id', $id)
                    ->groupBy('a.id')
                    ->get(['a.id', 'n.created_date as notification_date', 'a.date_delivered', DB::raw('CONCAT(t.form," - ",t.short_descrip) as appr_type_name'), 'a.final_appraisal_borrower_sendtopostalmail_amount as amount', 'a.mail_paid_amount as paidamount',
                    'a.ordereddate as dateordered' , 'a.loanrefnum', 'a.borrower', 'a.propaddress1 as address',
                    'a.propcity as city', 'a.propstate as state']);
    }

    public function getAppraisalOrdersQuery()
    {
        return Notification::from('document_vault_notification as n')
                            ->leftJoin('appr_order as a', 'a.id', '=', 'n.order_id')
                            ->leftJoin('appr_type as t', 'a.appr_type', '=', 't.id')
                            ->leftJoin('user_groups as g', 'g.id', '=', 'a.groupid')
                            ->leftJoin('user_group_lender as l', 'l.id', '=', 'a.lender_id')
                            ->whereRaw("(a.final_appraisal_borrower_sendtoemail =  'Y' OR a.final_appraisal_borrower_sendtopostalmail =  'Y')")
                            ->whereRaw("(a.final_appraisal_borrower_sendtopostalmail_amount - a.mail_paid_amount) > 0");
    }

    /**
     * Get appraisal orders by lender
     *
     */
    protected function getAppraisalLenderOrders($id)
    {
        $lenderOrders = $this->getAppraisalOrdersByLender($id)->toArray();

        $orders = $this->processOrdersInformation($lenderOrders);

        return $orders;
    }

    public function getAppraisalOrderGroups()
    {
        return $this->getAppraisalOrdersQuery()
                    ->where('show_group_as_lender', '=', '1')
                    ->groupBy('g.id')
                    ->get(['g.id', 'g.descrip as name', 'g.enable_docuvault'])->toArray();
    }

    public function getAppraisalOrderLenders()
    {
        return $this->getAppraisalOrdersQuery()
                    ->where('l.id', '>', '1')
                    ->groupBy('l.id')
                    ->get(['l.id', 'l.lender as name'])->toArray();
    }

    /**
     * Get appraisal orders by group
     *
     */
    protected function getAppraisalGroupOrders($id)
    {
        $groupOrders = $this->getAppraisalOrdersByGroup($id)->toArray();
        ;

        $orders = $this->processOrdersInformation($groupOrders);

        return $orders;
    }

    /**
    * Get appraisal group orders
    *
    */
    protected function getAppraisalOrdersByGroup($id)
    {
        return $this->getAppraisalOrdersQuery()
                    ->where('show_group_as_lender', '1')
                    ->where('groupid', $id)
                    ->groupBy('a.id')
                    ->get(['a.id', 'n.created_date as notification_date', 'a.date_delivered', DB::raw('CONCAT(t.form," - ",t.short_descrip) as appr_type_name'), 'a.final_appraisal_borrower_sendtopostalmail_amount as amount', 'a.mail_paid_amount as paidamount',
                    'a.ordereddate as dateordered' , 'a.loanrefnum', 'a.borrower', 'a.propaddress1 as address',
                    'a.propcity as city', 'a.propstate as state']);
    }

    /**
    * Get docuvault orders query
    *
    */
    protected function getDocuVaultOrdersQuery()
    {
        return Notification::from('document_vault_notification as n')
                            ->leftJoin('appr_docuvault_order as a', 'a.id', '=', 'n.order_id')
                            ->leftJoin('user_groups as g', 'g.id', '=', 'a.groupid')
                            ->leftJoin('appr_type as t', 'a.appr_type', '=', 't.id')
                            ->leftJoin('user_group_lender as l', 'l.id', '=', 'a.lender_id')
                            ->whereRaw("(a.final_appraisal_borrower_sendtoemail= 'Y' OR a.final_appraisal_borrower_sendtopostalmail= 'Y' )")
                            ->whereRaw("(a.invoicedue - a.paid_amount) > 0");
    }

    /**
     * Get docuvault order groups
     *
     */
    protected function getDocuVaultOrderGroups()
    {
        return $this->getDocuVaultOrdersQuery()
                    ->where('show_group_as_lender', '1')
                    ->groupBy('g.id')
                    ->get(['g.id', 'g.descrip as name'])->toArray();
    }

    /**
     * Get docuvault order lenders
     *
     */
    protected function getDocuVaultOrderLenders()
    {
        return $this->getDocuVaultOrdersQuery()
                    ->where('l.id', '>', '0')
                    ->groupBy('l.id')
                    ->get(['l.id', 'l.lender as name'])->toArray();
    }

    /**
     * Get docuvault orders by group
     *
     */
    protected function getDocuVaultGroupOrders($id)
    {
        $groupOrders = $this->getDocuVaultOrdersByGroup($id);

        $orders = $this->processOrdersInformation($groupOrders);

        return $orders;
    }

    /**
     * Get docuvault orders by lender
     *
     */
    protected function getDocuVaultLenderOrders($id)
    {
        $lenderOrders = $this->getDocuVaultOrdersByLender($id);

        $orders = $this->processOrdersInformation($lenderOrders);
        
        return $orders;
    }

    /**
     * Get docuvault group orders
     *
     */
    protected function getDocuVaultOrdersByGroup($id)
    {
        return $this->getDocuVaultOrdersQuery()
                    ->where('show_group_as_lender', '1')
                    ->where('groupid', $id)
                    ->groupBy('a.id')
                    ->get(['a.id' , 'n.created_date as notification_date', 'a.invoicedue as amount' , 'a.paid_amount as paidamount',
                            'a.ordereddate as dateordered', 'a.loanrefnum', 'a.ordereddate as date_delivered', DB::raw('CONCAT(t.form," - ",t.short_descrip) as appr_type_name'), 'a.borrower',
                            'a.propaddress1 as address', 'a.propcity as city', 'a.propstate as state'])->toArray();
    }

    /**
     * Get docuvault lender orders
     *
     */
    protected function getDocuVaultOrdersByLender($id)
    {
        return $this->getDocuVaultOrdersQuery()
                    ->where('lender_id', $id)
                    ->groupBy('a.id')
                    ->get(['a.id' , 'n.created_date as notification_date' , 'a.invoicedue as amount' , 'a.paid_amount as paidamount',
                            'a.ordereddate as dateordered', 'a.loanrefnum', 'a.ordereddate as date_delivered', DB::raw('CONCAT(t.form," - ",t.short_descrip) as appr_type_name'), 'a.borrower',
                            'a.propaddress1 as address', 'a.propcity as city', 'a.propstate as state'])->toArray();
    }

    /**
     *
     * @return array
     */
    public function docuVaultReceivablesDataTables()
    {
        $items = [];

        $groups = $this->getAppraisalOrderGroups();
        $lenders = $this->getAppraisalOrderLenders();
        
        if ($groups) {
            foreach ($groups as $group) {
                if ($group['enable_docuvault']) {
                    $ordersGroups = $this->getAppraisalGroupOrders($group['id']);

                    if (count($ordersGroups)) {
                        $items[ 'g_' . $group['id']] =  array_merge($group, ['type' => 'group', 'orders' => $ordersGroups]);
                    }
                }
            }
        }

        if ($lenders) {
            foreach ($lenders as $lender) {
                $ordersLenders = $this->getAppraisalLenderOrders($lender['id']);

                if (count($ordersLenders)) {
                    $items[ 'l_' . $lender['id']] =  array_merge($lender, ['type' => 'lender', 'orders' =>$ordersLenders]);
                }
            }
        }

        $groups = $this->getDocuVaultOrderGroups();
        
        $lenders = $this->getDocuVaultOrderLenders();

        if ($groups) {
            foreach ($groups as $group) {
                $orders = $this->getDocuVaultGroupOrders($group['id']);

                if (isset($items[ 'g_' . $group['id']])) {
                    $items[ 'g_' . $group['id']]['orders'] = array_merge($items[ 'g_' . $group['id']]['orders'], $orders);
                } else {
                    $items[ 'g_' . $group['id']] = array_merge($group, ['type' => 'group', 'orders' => $orders]);
                }
            }
        }

        if ($lenders) {
            foreach ($lenders as $lender) {
                $orders = $this->getDocuVaultLenderOrders($lender['id']);

                if (isset($items[ 'l_' . $lender['id']])) {
                    $items[ 'l_' . $lender['id']]['orders'] = array_merge($items[ 'l_' . $lender['id']]['orders'], $orders);
                } else {
                    $items[ 'l_' . $lender['id']] = array_merge($lender, ['type' => 'lender', 'orders' => $orders]);
                }
            }
        }
       
        // Process Clients stats
        foreach ($items as $i => $data) {
            $items[$i] = array_merge($data, $this->processClientStats($data['orders']));
            $items[$i]['id'] = $i;
            
            // Unset orders to free some memory
            unset($items[$i]['orders']);
        }

        return $items;
    }

    /**
     * Query all records both appraisals and docuvault
     * @param $id
     */
    protected function getRecordsByClient($id)
    {
        $orders = [];
        $clientId = $this->getCleanId($id);

        if ($this->isLender($id)) {
            $orders = array_merge($this->getAppraisalOrdersByLender($clientId)->toArray(), $this->getDocuVaultOrdersByLender($clientId));
        } elseif ($this->isGroup($id)) {
            $orders = array_merge($this->getAppraisalOrdersByGroup($clientId)->toArray(), $this->getDocuVaultOrdersByGroup($clientId));
        }
        
        // Process additional info
        $orders = $this->processOrdersInformation($orders);
        $total = 'balance';

        return ['orders' => $orders, 'total' => $total];
    }

    /**
     * Create single client array data provider with all records
     *
     */
    public function getClientRecordsListDataProvider($dataId)
    {
        $data = [];

        foreach ($dataId as $id => $value) {
            $row = $this->getRecordsByClient($id);
            $orders = $row['orders'];
            $total = $row['total'];
            
            // Get client record
            if ($this->isLender($id)) {
                $client = Lender::findOrFail($this->getCleanId($id));
                if ($client) {
                    $title = $client->lender;
                    $address = $client->lender_address1;
                    $address2 = $client->lender_address2;
                    $city = $client->lender_city;
                    $state = $client->lender_state;
                    $zip = $client->lender_zip;
                }
            } elseif ($this->isGroup($id)) {
                $client = Client::findOrFail($this->getCleanId($id));
                if ($client) {
                    $title = $client->descrip;
                    $address = $client->address1;
                    $address2 = $client->address2;
                    $city = $client->city;
                    $state = $client->state;
                    $zip = $client->zip;
                }
            }

            $clientData = [
                'title' => $title,
                'address' => $address,
                'address2' => $address2,
                'city' => $city,
                'state' => $state,
                'zip' => $zip,
            ];

            $data[$id] = ['orders' => $orders, 'title' => $title, 'client_data' => $clientData];
        }
        

        return $data;
    }

    public function processClientStats($orders)
    {
        // Init stats
        $stats = [
            'count' => [
                '60' => 0,
                '60-90' => 0,
                '90-120' => 0,
                '120' => 0,
                'pastdue' => 0,
                'total' => 0,
            ],
            'totals' => [
                '60' => 0,
                '60-90' => 0,
                '90-120' => 0,
                '120' => 0,
                'pastdue' => 0,
                'total' => 0,
            ],
        ];

        // Loop orders and calculate the amounts and totals
        if ($orders) {
            foreach ($orders as $order) {
                $days = $order['days'];

                if ($days >= 120) {
                    $stats['count']['120']++;
                    $stats['totals']['120'] += $order['amount'];
                } elseif ($days >= 90 && $days < 120) {
                    $stats['count']['90-120']++;
                    $stats['totals']['90-120'] += $order['amount'];
                } elseif ($days >= 60 && $days < 90) {
                    $stats['count']['60-90']++;
                    $stats['totals']['60-90'] += $order['amount'];
                } elseif ($days < 60) {
                    $stats['count']['60']++;
                    $stats['totals']['60'] += $order['amount'];
                }

                // Add to past due
                if ($days > 60) {
                    $stats['count']['pastdue']++;
                    $stats['totals']['pastdue'] += $order['amount'];
                }

                // Add to totals
                $stats['count']['total']++;
                $stats['totals']['total'] += $order['amount'];
            }
        }

        return ['stats' => $stats, 'count' => $stats['count']['total']];
    }

    /**
     * Process orders information
     *
     */
    protected function processOrdersInformation($orders)
    {
        if ($orders) {
            foreach ($orders as $i => $order) {
                $orders[$i] = array_merge($order, $this->processOrderInformation($order));

                // Filter set
                if ($this->days && $orders[$i]['dayscategory'] != $this->days) {
                    unset($orders[$i]);
                }

                // Filter past due
                if ($this->pastdue && !$orders[$i]['pastdue']) {
                    unset($orders[$i]);
                }

                // Filter by ids
                if ($this->ids && !in_array($orders[$i]['id'], $this->ids)) {
                    unset($orders[$i]);
                }
            }
        }

        return $orders;
    }

    /**
    * Calculate the difference between two days
    * @return int
    */
    public function dateDifference($from, $to)
    {
        return floor(($to - ($from))/3600/24);
    }

    /**
     * Process specific info for each order
     * @param array $order
     * @return array
     */
    protected function processOrderInformation($order)
    {
        
        // Calcualte date difference
        $dateDiff = $this->dateDifference($order['notification_date'], time());
        $order['days'] = $dateDiff;
        $order['pastdue'] = false;

        // Figure out category based on days
        if ($order['days'] >= 120) {
            $order['dayscategory'] = '120';
            $order['pastdue'] = true;
        } elseif ($order['days'] >= 90 && $order['days'] < 120) {
            $order['dayscategory'] = '90-120';
            $order['pastdue'] = true;
        } elseif ($order['days'] >= 60 && $order['days'] < 90) {
            $order['dayscategory'] = '60-90';
            $order['pastdue'] = true;
        } elseif ($order['days'] < 60) {
            $order['dayscategory'] = '60';
        }
        
        return $order;
    }

    /**
     * Calculate the difference between two days
     * @return int
     */
    public function dataCsv($data)
    {
        $dataCsv = [];

        $items = $this->getClientRecordsListDataProvider($data);
        
        foreach ($data as $id => $values) {
            foreach ($values as $key => $orderId) {
                foreach ($items[$id]['orders'] as $order) {
                    if ($order['id'] == $orderId) {
                        $dataCsv[$id][$orderId] = $order;
                    }
                };
            }
        }
        return $this->generateItems($dataCsv);
    }

    /**
     * get items for download CSV
     * @param $data
     */
    public function generateItems($data)
    {
        $list = [];

        $headers = $this->includingHeaders();

        // Loop orders
        foreach ($data as $orders) {
            foreach ($orders as $key => $order) {
                $list[] = [
                    'id' => $order['id'],
                    'loanrefnum' => $order['loanrefnum'],
                    'dateordered' => $order['dateordered'],
                    'datecompleted' => $order['datecompleted'],
                    'notification_date' => $order['notification_date'],
                    'borrower' => $order['borrower'],
                    'address' => $order['address'] . ' ' . $order['city'] . ', ' . $order['state'],
                    'balance' => $order['amount'] - $order['paidamount']
                ];
            }
        }

        $dataCsv = [];

        foreach ($list as $key => $value) {
            foreach ($headers as $keyHead => $valueHead) {
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
                'id' => 'ID',
                'loanrefnum' => 'Loan #',
                'dateordered' => 'Date Ordered',
                'datecompleted' => 'Date Completed',
                'notification_date' => 'Notification Date',
                'borrower' => 'Borrower',
                'address' => 'Property Address',
                'balance' => 'Balance'
        ];
    }

    /**
     * headers
     * @param $data
     */
    public function getDataForStatments($data)
    {
        $dataStatments = [];

        $items = $this->getClientRecordsListDataProvider($data);

        $ordersClientStats = [];

        foreach ($data as $id => $values) {
            foreach ($values as $key => $orderId) {
                foreach ($items[$id]['orders'] as $order) {
                    if ($order['id'] == $orderId) {
                        array_push($ordersClientStats, $order);
                        $dataStatments[$id][$orderId] = $order;
                        $dataStatments[$id]['client_data'] = $items[$id]['client_data'];
                    }
                }
            }

            $dataStatments[$id]['counts'] = $this->processClientStats($ordersClientStats);
        }
        
        return $dataStatments;
    }
}
