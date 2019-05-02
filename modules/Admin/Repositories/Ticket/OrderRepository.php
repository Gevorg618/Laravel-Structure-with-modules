<?php

namespace Modules\Admin\Repositories\Ticket;

use App\Models\Customizations\Status;
use App\Models\Tools\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Modules\Admin\Contracts\Ticket\OrderContract;
use App\Models\Appraisal\Order;
use App\Models\AlternativeValuation\Order as AltOrder;
use Carbon\Carbon;
use App\Models\Users\User;
use Yajra\DataTables\Datatables;
use App\Models\ReconsiderationLog\ReconsiderationLog;
use App\Models\Appraisal\OrderLog;
use Modules\Admin\Repositories\Tools\SettingRepository;

class OrderRepository implements OrderContract
{
    private $order;

    const TEMPORDER = 9;
    private $settingRepo;

    /**
     * OrderRepository constructor.
     *
     * @param Order $order
     */
    public function __construct()
    {
        $this->order = new Order();
        $this->settingRepo = new SettingRepository();
    }

    /**
     * Get Delayed Data
     * @return mixed
     */

    public function getDelayedData()
    {
        $query = $this->order->select('appr_order.*',
                        't.descrip as team_title',
                        't.id as team_id'
                    )->leftJoin(
                        'user as u',
                        'appr_order.orderedby',
                        '=',
                        'u.id'
                    )->leftJoin(
                        'user_groups as g',
                        'u.groupid',
                        '=',
                        'g.id'
                    )->leftJoin(
                        'admin_team_client as c',
                        'g.id',
                        '=',
                        'c.user_group_id'
                    )->leftJoin(
                        'admin_teams as t',
                        'c.team_id',
                        '=',
                        't.id'
                    )->where('appr_order.is_delayed', 1);
        return $query
                ->orderBy('ordereddate', 'DESC')
                ->with('lastLog')
                ->with('apprClient')
                ->with('apprStatus')
                ->with('adminTeamClient')
                ->get();
    }

    /**
     * @param string $term
     * @return array
     */
    public function searchOrders($term)
    {
        $orders = [];

        $rows = Order::ofSearch($term)
            ->orderBy('appr_order.id', 'desc')
            ->limit(20)
            ->get();

        foreach ($rows as $row) {
            $orders[] = [
                'label' => sprintf(
                    "%s - %s - %s - %s - %s",
                    $row->id,
                    $row->formatOrderDate,
                    $row->shortAddress,
                    $row->statusName,
                    $row->apprTypeShortName
                ),
                'value' => 'appr-' . $row->id
            ];
        }

        $rows = AltOrder::ofSearch($term)->whereNotIn('alt_order.status', [9])
            ->orderBy('alt_order.id', 'desc')
            ->limit(20)
            ->get();

        foreach ($rows as $row) {
            $orders[] = [
                'label' => sprintf(
                    "%s - %s - %s - %s - %s",
                    $row->id,
                    $row->formatOrderDate,
                    $row->shortAddress,
                    $row->statusName,
                    $row->apprTypeShortName
                ),
                'value' => 'al-' . $row['id']];
        }

        return $orders;
    }

    /**
     * @param string $term
     * @return array
     */
    public function getUserTransferableOrders($userType, $id)
    {

        if($userType == User::USER_TYPE_CLIENT) {

            $orders = $this->order->where('orderedby' , $id)->get();

        } else if ($userType == User::USER_TYPE_APPRAISER) {

            $orders = $this->order->where('acceptedby' , $id)->get();

        } else {

            return  [
                'success' => false,
                'message' => "not found"
            ];
        }

        return  [
                'success' => true,
                'orders' => $orders
            ];
    }

    /**
     * @param int $id
     * @return Order
     */
    public function getOrder($id)
    {
       return $this->order->find($id);
    }

    /**
     * @param $acceptedby
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getOrderByAcceptedBy($acceptedby)
    {
        return $this->order->where('acceptedby', $acceptedby)->first();
    }

    /**
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update($id, $data)
    {
        $this->order->where('id', $id)->update($data);
    }

    /**
     * @param array $data
     * @return array
     */
    public function googleGeoCodeOverQueryLimit()
    {
        $orders = $this->order->where('pos_lat', null)
                    ->orWhere('pos_long', null)
                    ->orWhere('pos_lat', 0.0000000000)
                    ->orWhere('pos_long', 0.0000000000)
                    ->where('propaddress1', '!=', '')
                    ->where('propzip', '>', 0)
                    ->where('geocode_is_failed', 0)
                    ->whereNotIn('status', [9, 10, 6]);
        return $orders;
    }

    /**
     * get custom pages for dataTable
     *
     * @return array $googleGeoCodeOrderDataTable
     */
    public function googleGeoCodeOrderDataTable()
    {
        $orders = $this->googleGeoCodeOverQueryLimit();
        $customPagesDataTables = Datatables::of($orders)
                ->editColumn('options', function ($order) {
                    return view('admin::geo.google-geo-coding.partials._options', ['order' => $order])->render();
                })
                ->editColumn('id', function ($order) {
                    return $order->id;
                })
                ->editColumn('date', function ($order) {
                    return  $order->ordereddate;
                })
                ->editColumn('address', function ($order) {
                    return $order->userData ? $order->userData->comp_address. ',' . $order->propaddress1. ' '  .$order->propaddress2. ' , ' .$order->propcity. ' , ' .$order->propstate : 'N/A';
                })
                ->editColumn('status', function ($order) {
                    return $order->orderStatus ? $order->orderStatus->descrip : 'N/A';
                })
                ->editColumn('failed', function ($order) {
                    return $order->geocode_is_failed ? "Yes" : "No";
                })
                ->rawColumns(['options'])
                ->make(true);

        return $customPagesDataTables;
    }

    /**
     *
     * @param object $order
     * @return
     */
    public function updateApprOrderLatLong($order)
    {

        // Get company address
        $geoAddress = $order->userData ? $order->userData->comp_address. ',' .$order->propaddress1 . ', ' . $order->propcity . ', ' . $order->propstate . ', ' . $order->propzip : null;

        $geoAddress = trim($geoAddress, ',');
        $geoAddress = trim($geoAddress);
        $geoAddress = str_replace('  ', ' ', $geoAddress);

        if($geoAddress) {

            $geoCode = geoCode($geoAddress);

            if ($geoCode) {

                $apprLatPos = $geoCode['lat'];
                $apprLongPos = $geoCode['long'];

                // Update
                $this->update($order->id, ['pos_lat' => $apprLatPos, 'pos_long' => $apprLongPos]);
            }

            if(!isset($apprLatPos) || $apprLatPos <= 0) {

                $this->update($order->id, ['geocode_fail_count' => ($order->geocode_fail_count + 1), 'geocode_is_failed' => 1]);

            } else {

                $this->update($order->id, ['geocode_fail_count' => 0, 'geocode_is_failed' => 0]);

                $response = ['success' => true, 'data' => ['lat' => $apprLatPos, 'long' => $apprLongPos] ];
            }

        } else {
            $response = ['success' => false, 'message' => 'Geo Address not found'];
        }

        return $response;
    }

    /**
     *
     * @param object $order
     * @return
     */
    public function orderPosLatLon($order)
    {
        if ( ($order->pos_lat > 0)) {

            $response = [
                'success' => true,
                'data' => ['lat' => $order->pos_lat, 'long' => $order->pos_long]
            ];
        } else {
            $response = [
                'success' => false
            ];
        }
        return $response;
    }

    /**
     * @param array $ids
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getOrdersByIds($ids = [])
    {
        return Order::whereIn('id', $ids)->get();
    }


    /**
     * get statitistcs created (placed)  order
     *
     * @return object $calendar
     */
    public function getStatsCreatedOrders($fromDate = null, $toDate = null, $key=null)
    {

        $setting = $this->settingRepo->getSettingsByKey($key);

        $orders =  $this->order->whereNotIn('status', [9, 10, 20]);

        if ($setting) {
            $orders =  $orders->where('appr_type', $setting);
        }

        if ($fromDate && $toDate) {

            $orders->where('ordereddate', '>=',  $fromDate)
                   ->where('ordereddate', '<=', $toDate );
        }

        return $orders;
    }

    /**
     * get statitistcs canceled (placed)  order
     *
     * @return object $calendar
     */
    public function getStatsOrdersCanceled($fromDate = null, $toDate = null, $key=null)
    {
        $setting = $this->settingRepo->getSettingsByKey($key);

        $orders =  $this->order->whereIn('status', [10, 20]);

        if ($setting) {
            $orders =  $orders->where('appr_type', $setting);
        }

        if ($fromDate && $toDate) {

            $orders->where('date_canceled', '>=',  $fromDate)
                   ->where('date_canceled', '<=', $toDate);
        }

        return $orders;
    }

    /**
     * get statitistcs canceled (placed)  order
     *
     * @return object $calendar
     */
    public function getQcOrders($key=null)
    {

        $setting = $this->settingRepo->getSettingsByKey($key);

        $orders =  $this->order->whereIn('status', [10, 20]);

        if ($setting) {
            $orders =  $orders->where('appr_type', $setting);
        }

        return $orders;
    }

    /**
     * get statitistcs low margin  order
     *
     * @return object $calendar
     */
    public function getStatsLowMarginOrders($fromDate = null, $toDate = null, $key=null)
    {

        $setting = $this->settingRepo->getSettingsByKey($key);

        $orders =  $this->order->whereNotIn('status', [9, 10, 20])->selectRaw(\DB::raw("* , (invoicedue-split_amount) as margin"))->having('margin', '<', '100');

        if ($setting) {
            $orders =  $orders->where('appr_type', $setting);
        }

        if ($fromDate && $toDate) {

            $orders->where('accepteddate', '>=',  $fromDate)
                   ->where('accepteddate', '<=',  $toDate);
        }

        return $orders;
    }

    /**
     * get Accounting Stats Delivered CC
     *
     * @return object $calendar
     */
    function getAccountingStatsDeliveredCC($fromDate, $toDate, $key = null)
    {

        $setting = $this->settingRepo->getSettingsByKey($key);

        $orders =  $this->order->whereIn('status', [6])
                                ->where('date_delivered', '>=',  $fromDate)
                                ->where('date_delivered', '<=',  $toDate)
                                ->where('is_cod', 'N')
                                ->where('billmelater', 'N')
                                ->has('cimChechPayments', '=', 0 )
                                ->with(['fdPayments' => function($query){
                                    $query->where('ref_type', 'CHARGE')->where('is_success', 1);
                                }]);

        if ($setting) {
            $orders =  $orders->where('appr_type', $setting);
        }

        return $orders->count();
    }

    /**
     * get Accounting Stats Delivered CC Paid
     *
     * @return object $calendar
     */
    function getAccountingStatsDeliveredCheck($fromDate, $toDate, $key = null)
    {

        $setting = $this->settingRepo->getSettingsByKey($key);

        $orders =  $this->order->whereNotIn('status', [6])
                                ->where('date_delivered', '>=',  $fromDate)
                                ->where('date_delivered', '<=',  $toDate)
                                ->where('is_cod', 'N')
                                ->where('billmelater', 'N')
                                ->has('cimChechPayments');

        if ($setting) {
            $orders =  $orders->where('appr_type', $setting);
        }

        return $orders->count();
    }

     /**
     * get AccountingStatsDeliveredInvoice
     *
     * @return object $calendar
     */
    function getAccountingStatsDeliveredInvoice($fromDate, $toDate, $key = null)
    {

        $setting = $this->settingRepo->getSettingsByKey($key);

        $orders =  $this->order->whereIn('status', [6])
                                ->where('date_delivered', '>=',  $fromDate)
                                ->where('date_delivered', '<=',  $toDate)
                                ->where('is_cod', 'N')
                                ->where('billmelater', 'N');

        if ($setting) {
            $orders =  $orders->where('appr_type', $setting);
        }

        return $orders->count();
    }

    /**
     * get getAccountingStatsDeliveredCOD
     *
     * @return object $calendar
     */
    function getAccountingStatsDeliveredCOD($fromDate, $toDate, $key = null)
    {
        $setting = $this->settingRepo->getSettingsByKey($key);

        $orders =  $this->order->whereIn('status', [6])
                                ->where('date_delivered', '>=',  $fromDate)
                                ->where('date_delivered', '<=',  $toDate)
                                ->where('is_cod', 'Y');

        if ($setting) {
            $orders =  $orders->where('appr_type', $setting);
        }

        return $orders->count();
    }

    /**
     * get statitistcs assigned   order
     *
     * @return object $calendar
     */
    public function getStatsAssignedOrders($fromDate = null, $toDate = null, $key=null)
    {
        $setting = $this->settingRepo->getSettingsByKey($key);

        $orders =  $this->order->whereNotIn('status', [9, 10, 20]);

        if ($setting) {
            $orders =  $orders->where('appr_type', $setting);
        }

        if ($fromDate && $toDate) {

            $orders->where('accepteddate', '>=', $fromDate)
                   ->where('accepteddate', '<=', $toDate);
        }

        return $orders;
    }

    /**
     * get statitistcs completed order
     *
     * @return object $calendar
     */

    function getStatsCompletedOrders($fromDate = null, $toDate = null, $key=null)
    {
        $setting = $this->settingRepo->getSettingsByKey($key);

        $completedOrder =  $this->order->whereIn('status', [6]);

        if ($setting) {
            $completedOrder =  $completedOrder->where('appr_type', $setting);
        }

        if ($fromDate && $toDate) {

            $completedOrder->where('date_delivered', '>=',  $fromDate)
                   ->where('date_delivered', '<=',  $toDate);
        }

        return $completedOrder;
    }

    function accountingGetClientOrders()
    {
        $orders =  $this->order->whereIn('status', [6, 10, 14, 16, 17, 18, 20])
                    ->where('paid_amount',  '!=', 'invoicedue')->get();
         return $orders;
    }

    /**
     * get accounting stats deliveried invoced
     *
     * @return object
     */
    function getAccountingStatsDeliveredInvoiced($fromDate, $toDate, $key = null)
    {

        $setting = $this->settingRepo->getSettingsByKey($key);

        $orders =  $this->order->where('date_delivered', '>=',  $fromDate)
                   ->where('date_delivered', '<=',  $toDate)->whereNotIn('status', [9, 10]);

        if ($setting) {
            $orders =  $orders->where('appr_type', $setting);
        }

        return $orders->sum('invoicedue');
    }


    /**
     * get statitistcs completed order
     *
     * @return object
     */
    function getAccountingStatsAmountbyDate($fromDate = null, $toDate = null, $settingKey = null, $amountType = null)
    {

        $completedOrderInvoiceAmount =  $this->order->whereNotIn('status', [9, 6]);

        if ($settingKey) {

            $setting = $this->settingRepo->getSettingsByKey($settingKey);

            if ($setting) {
                $completedOrderInvoiceAmount =  $completedOrderInvoiceAmount->where('appr_type', $setting);
            }
        }


        if ($fromDate && $toDate) {

            $completedOrderInvoiceAmount->where('date_delivered', '>=',  $fromDate)
                   ->where('date_delivered', '<=',  $toDate);
        }

        $total = $completedOrderInvoiceAmount->sum($amountType);

        return $total;
    }


    /**

     * @param array $userIds
     * @param array $dateRange
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getOrderAmounts($userIds = [], $dateRange = [])
    {
        return Order::select(\DB::raw("
            SUM(split_amount) as sum, acceptedby
        "))->whereIn('acceptedby', $userIds)
            ->whereHas('appraisalPayment', function ($query) use ($userIds, $dateRange) {
                return $query->whereIn('apprid', $userIds)
                    ->whereBetween('paid', $dateRange);
            })->groupBy('acceptedby')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getDocuvaultAppraisalMonthlyReport()
    {
        return Order::select(\DB::raw("
            DATE_FORMAT(date_delivered, '%Y/%m') as del_date,
            SUM(final_appraisal_borrower_sendtopostalmail_amount) as total_invoice,
            SUM(mail_paid_amount) as total_paid
        "))->where('final_appraisal_borrower_sendtopostalmail', 'Y')
            ->where('date_delivered', '>', 0)
            ->groupBy('del_date')
            ->orderBy('del_date')->get();
    }

    /**
     * Get Order  Data
     * @return mixed
     */
    public function getFilteredData($data)
    {
        $selecting = [
            'appr_order.*',
            's.descrip as loanpurposetitle',
            \DB::raw('CONCAT(t.form," - ",t.descrip) AS appraisal'),
            'g.descrip as client',
            'st.descrip as statustitle',
            'team.descrip as teamtitle'
        ];
        $query = $this->order
                    ->leftJoin('loanpurpose as s', 'appr_order.loanpurpose', '=', 's.id')
                    ->leftJoin('appr_type as t', 'appr_order.appr_type', '=', 't.id')
                    ->leftJoin('user_groups as g', 'appr_order.groupid', '=', 'g.id')
                    ->leftJoin('order_status as st', 'appr_order.status', '=', 'st.id')
                    ->leftJoin('admin_team_client as tc', 'appr_order.groupid', '=', 'tc.user_group_id')
                    ->leftJoin('admin_teams as team', 'tc.team_id', '=', 'team.id');

                    if(!is_null($data)) {
                        //setting for escalated-pipeline
                        if (isset($data['escalated-pipeline']) && $data['escalated-pipeline']) {
                            if(Setting::getSetting('escalated_pipeline_appr_type')) {
                                $query =  $query->whereIn('appr_order.appr_type', Setting::getSetting('escalated_pipeline_appr_type'));
                            }
                            if(Setting::getSetting('escalated_pipeline_status')) {
                                $query =  $query->whereIn('appr_order.status', Setting::getSetting('escalated_pipeline_status'));
                            }
                            if(Setting::getSetting('escalated_pipeline_loan_reason')) {
                                $query =  $query->whereIn('appr_order.loanpurpose', Setting::getSetting('escalated_pipeline_loan_reason'));
                            }
                            $query = $query->where('appr_order.mgrescalate', 'Y');
                        }
                        //setting for purchase-pipeline
                        if (isset($data['purchase-pipeline']) && $data['purchase-pipeline']) {
                            if (Setting::getSetting('purchase_pipeline_loan_reason')) {
                                $query =  $query->whereRaw("(`appr_order`.`is_new_construction` = '1' or `appr_order`.`loanpurpose` in ('" . Setting::getSetting('purchase_pipeline_loan_reason') ."'))");
                            } else {
                                $query =  $query->where('appr_order.is_new_construction', 1);
                            }
                            if (Setting::getSetting('purchase_pipeline_appr_status')) {
                                $query =  $query->whereIn('appr_order.status', [Setting::getSetting('purchase_pipeline_appr_status')]);
                            }
                            array_push($selecting, 'delay.delay_date');
                            $query = $query
                                ->leftJoin('appr_order_files as f', \DB::raw("(`f`.`order_id` = `appr_order`.`id` and `f`.`document_type`"), \DB::raw("".getDocumentTypeIdByCode('SALES_CONTRACT'). ")"))
                                ->leftJoin('appr_dashboard_delay_order as delay', \DB::raw("(`delay`.`delay_date` >= " . Carbon::now()->startOfDay()->timestamp ." and `delay`.`delay_date` <= ". Carbon::now()->endOfDay()->timestamp ." and `delay`.`orderid`"), \DB::raw("`appr_order`.`id`)"));
                        }
                        //setting for unassigned-pipeline
                        if (isset($data['unassigned-pipeline']) && $data['unassigned-pipeline']) {
                            $selecting = [
                                \DB::raw('IF(`appr_order`.`unassigned_date`, `appr_order`.`unassigned_date`, unix_timestamp(`appr_order`.`ordereddate`)) as assigned_date, `appr_order`.*, `s`.`descrip` as loanpurposetitle, CONCAT(`t`.`form`," - ",`t`.`descrip`) as appraisal, `g`.`descrip` as client, `st`.`descrip` as statustitle, `team`.`descrip` as teamtitle, COUNT(DISTINCT `invites`.`id`) as total_invites')
                            ];
                            $query = $query
                                ->leftJoin('appr_order_invites as invites', 'invites.order_id', '=', 'appr_order.id');
                            $query = $query->where('status', 8)->groupBy('appr_order.id')->with('invites')->with('tickets');
                            // auto select
                            if(isset($data['autoselect']) && $data['autoselect'] !== '') {
                                $query = $query->having('total_invites', '>=', 20);
                            }
                        }
                        // Rush
                        if(isset($data['is_rush']) && $data['is_rush'] !== '') {
                            $query = $query->where('appr_order.is_rush', $data['is_rush']);
                        }
                        // Escalated
                        if(isset($data['escalated']) && $data['escalated'] !== '') {
                            $escalated = $data['escalated'] ? 'Y' : 'N';
                            $query = $query->where('appr_order.mgrescalate', $escalated);
                        }
                        // pending_review
                        if(isset($data['pending_review']) && $data['pending_review'] !== '') {
                            $query = $query->where('appr_order.is_contract_reviewed', $data['pending_review']);
                        }
                        // status
                        if(isset($data['status']) && $data['status'] !== '') {
                            $query = $query->where('appr_order.status', $data['status']);
                        }
                        // Team
                        if(isset($data['team']) && $data['team'] !== '' && !is_array($data['team'])) {
                            $query = $query->where('team.id', $data['team']);
                        } elseif(isset($data['team']) && is_array($data['team'])) {
                            $query =  $query->whereIn('team.id', $data['team']);
                        }
                        // client
                        if(isset($data['client']) && $data['client'] !== '' && !is_array($data['client'])) {
                            $query = $query->where('appr_order.groupid', $data['client']);
                        } elseif(isset($data['client']) && is_array($data['client'])) {
                            $query =  $query->whereIn('appr_order.groupid', $data['client']);
                        }
                        // due date
                        if(isset($data['due_date']) && $data['due_date'] !== '') {
                            $query = $query->where('appr_order.due_date', '!=', '');
                            $query = $query->where('appr_order.due_date', '<=', Carbon::parse($data['due_date'])->timestamp);
                        }
                        // quick filter
                        if(isset($data['quick_filter']) && $data['quick_filter'] !== '') {
                            if($data['quick_filter'] == 'today') {
                                $query = $query->where('appr_order.due_date', '>', 0);
                                $query = $query->where(\DB::raw("FROM_UNIXTIME(appr_order.due_date, '%Y-%m-%d')"), '=', Carbon::now()->format('Y-m-d'));
                            } elseif($data['quick_filter'] == 'past') {
                                $query = $query->where('appr_order.due_date', '>', 0);
                                $query = $query->where(\DB::raw('FROM_UNIXTIME(appr_order.due_date, "%Y-%m-%d")'), '<', Carbon::now()->format('Y-m-d'));
                            }
                        }
                        //is_revisit_today
                        if(isset($data['is_revisit_today']) && $data['is_revisit_today'] !== '') {
                            if($data['is_revisit_today'] == 1) {
                                 $query = $query->whereNotNull('delay.delay_date');
                            } else {
                                $query = $query->whereNull('delay.delay_date');
                            }
                        }
                        // loan reason
                        if (isset($data['loanreason']) && $data['loanreason'] !== '') {
                            $query =  $query->whereIn('appr_order.loanpurpose', $data['loanreason']);
                        }
                        // state
                        if (isset($data['state']) && $data['state'] !== '') {
                            $query =  $query->whereIn('appr_order.propstate', $data['state']);
                        }
                        // time zone
                        if (isset($data['timezone']) && $data['timezone'] !== '') {
                            $list = [];
                            foreach($data['timezone'] as $zone) {
                                $states = getStatesInRegion($zone);
                                if(count($states)) {
                                    $states = array_keys($states);
                                    foreach($states as $state) {
                                        $list[] = $state;
                                    }
                                }
                            }
                            $query =  $query->whereIn('appr_order.propstate', $list);
                        }
                    }
                    return $query->select($selecting)->get();
    }

    public function getApprOrderById($orderId) {
        $row = $this->order->where('id', $orderId)->first();
        return $row && $row->id ? $row : '';
    }

    /**
     * @return $this
     */
    public function buildAccountsPayableQuery()
    {
        return Order::from('appr_order as a')
            ->select(\DB::raw("
                a.id,
                a.acceptedby as appr_accepted,
                a.invoicedue,
                a.split_amount as amount,
                a.paid_amount,
                FORMAT(a.invoicedue-a.paid_amount, 2) as balance,
                FORMAT(a.invoicedue-a.split_amount, 2) as margin,
                a.propaddress1,
                a.propaddress2,
                a.propcity,
                a.propstate,
                a.propzip,
                CONCAT(TRIM(CONCAT(a.propaddress1,' ',a.propaddress2)), ', ', a.propcity, ', ', a.propstate,' ',a.propzip) as fulladdress,
                a.borrower,
                a.date_delivered,
                a.ordereddate,
                s.descrip as status,
                appr.firstname,
                appr.lastname,
                appr.company,
                appr.ein,
                CONCAT(appr.firstname,' ',appr.lastname) as fullname,
                g.descrip as company_name,
                CASE
                    WHEN a.date_delivered THEN a.date_delivered
                    WHEN a.completed THEN a.completed
                    WHEN a.submitted THEN a.submitted
                ELSE a.ordereddate
                END as completed_date
            "))->leftJoin(
                'user as u',
                'u.id',
                '=',
                'a.orderedby'
            )->leftJoin(
                'user_data as appr',
                'appr.user_id',
                '=',
                'a.acceptedby'
            )->leftJoin(
                'user_groups as g',
                'g.id',
                '=',
                'a.groupid'
            )->leftJoin(
                'order_status as s',
                'a.status',
                '=',
                's.id'
            )->whereNull('a.appr_paid')
            ->where('a.split_amount', '>', 0)
            ->whereNotNull('a.acceptedby')
            ->where('a.acceptedby', '>', 0)
            ->where('a.hide_from_payables', 0);
    }

    /**
     * @param Builder $query
     * @param $from
     * @param $to
     * @param array $clients
     * @param array $states
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function finalizeAccountsPayableQuery(Builder $query, $from, $to, $clients = [], $states = [])
    {
        $payableStatuses = Setting::getSetting('appraisal_payables_statuses');
        if ($payableStatuses) {
            $payableStatuses = explode(',', $payableStatuses);
            $query = $query->whereIn('status', $payableStatuses);
        }
        if($from && $to) {
            $query = $query->whereRaw(
                sprintf("IF(status = %s, (date_delivered >= '%s 00:00:00' AND date_delivered <= '%s 23:23:59'), date_delivered IS NOT NULL)", Order::STATUS_APPRAISAL_COMPLETED, $from, $to)
            );
        } elseif(!$from && $to) {
            $query = $query->whereRaw(
                sprintf("IF(status = %s, (date_delivered <= '%s 23:23:59'), date_delivered IS NOT NULL)", Order::STATUS_APPRAISAL_COMPLETED, $to)
            );
        }
        if ($states) {
            $query = $query->whereIn('propstate', $states);
        }

        if ($clients) {
            $query = $query->whereHas('groupData', function ($q) use ($clients) {
                return $q->whereIn('user_groups.id', $clients);
            });
        }

        return $query->orderBy('fullname')
            ->orderBy('date_delivered')
            ->get();
    }

    public function getBatchCheckData($from, $to, $type, $clients)
    {
        $orders = Order::whereNotIn('status', [self::TEMPORDER])
            ->batchCheckDates($from, $to, $type);
        if ($clients) {
            $orders = $orders->whereHas('groupData', function ($query) use ($clients) {
                return $query->whereIn('id', $clients);
            });
        }
        return $orders->orderBy('date_delivered')->get();

    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $clients
     * @return Collection
     */
    public function getOrdersForBatch($dateFrom, $dateTo, $clients)
    {
        $orders = Order::with([
            'client',
            'groupData'
        ])->where('status', '<>', Order::STATUS_TEMP)
            ->whereHas('notifications', function ($query) use ($dateFrom, $dateTo){
                return $query->whereBetween('created_date', [
                    $dateFrom,
                    $dateTo
                ]);
            })->where(function ($query) {
                return $query->where('final_appraisal_borrower_sendtoemail', 'Y')
                    ->orWhere('final_appraisal_borrower_sendtopostalmail', 'Y');
            });
        if ($clients) {
            $orders = $orders->whereHas('groupData', function ($query) use ($clients) {
                return $query->whereIn('user_groups.id', $clients);
            });
        }
        return $orders->get()->sortBy(function (Order $item, $key) {
            return $item->notifications()->orderBy('created_date');
        });
    }

    public function getOrderById($id)
    {
        return Order::find($id);
    }

    /*
     * @param $dateType
     * @return Order
     */
    public function getFullForDeliverySalesReport($dateFrom, $dateTo, $dateType)
    {
        return Order::select(\DB::raw(
            "COUNT(id) as total_orders,
            SUM(invoicedue) as total_amount"
        ))->where('status', Order::STATUS_APPRAISAL_COMPLETED)
            ->whereColumn('paid_amount', '>=', 'invoicedue')
            ->getDateCondition($dateFrom, $dateTo, $dateType)->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @return Order
     */
    public function getBalanceForDeliverySalesReport($dateFrom, $dateTo, $dateType)
    {
        return Order::select(\DB::raw(
            "COUNT(id) as total_orders,
            SUM(invoicedue-paid_amount) as total_amount"
        ))->where('status', Order::STATUS_APPRAISAL_COMPLETED)
            ->where('paid_amount', '>', 0)
            ->whereColumn('paid_amount', '<', 'invoicedue')
            ->getDateCondition($dateFrom, $dateTo, $dateType)->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @return Order
     */
    public function getUnpaidForDeliverySalesReport($dateFrom, $dateTo, $dateType)
    {
        return Order::select(\DB::raw(
            "COUNT(id) as total_orders,
            SUM(invoicedue) as total_amount"
        ))->where('status', Order::STATUS_APPRAISAL_COMPLETED)
            ->where('paid_amount', 0)
            ->getDateCondition($dateFrom, $dateTo, $dateType)->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @return Order
     */
    public function getPaidForDeliverySalesReport($dateFrom, $dateTo, $dateType)
    {
        return Order::select(\DB::raw(
            "COUNT(id) as total_orders,
            SUM(paid_amount) as total_amount"
        ))->where('status', Order::STATUS_APPRAISAL_COMPLETED)
            ->getDateCondition($dateFrom, $dateTo, $dateType)->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @return Order
     */
    public function getOverallForDeliverySalesReport($dateFrom, $dateTo, $dateType)
    {
        return Order::select(\DB::raw(
            "COUNT(id) as total_orders,
            SUM(invoicedue) as total_amount"
        ))->where('status', Order::STATUS_APPRAISAL_COMPLETED)
            ->getDateCondition($dateFrom, $dateTo, $dateType)->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @return Order
     */
    public function getBalancePaidFullForDeliverySalesReport($dateFrom, $dateTo, $dateType)
    {
        return Order::select(\DB::raw(
            "COUNT(id) as total_orders,
            SUM(invoicedue) as total_amount"
        ))->where('status', Order::STATUS_APPRAISAL_COMPLETED)
            ->where('paid_amount', '>', 0)
            ->whereColumn('paid_amount', '<', 'invoicedue')
            ->getDateCondition($dateFrom, $dateTo, $dateType)->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @return Order
     */
    public function getBalancePaidForDeliverySalesReport($dateFrom, $dateTo, $dateType)
    {
        return Order::select(\DB::raw(
            "COUNT(id) as total_orders,
            SUM(paid_amount) as total_amount"
        ))->where('status', Order::STATUS_APPRAISAL_COMPLETED)
            ->where('paid_amount', '>', 0)
            ->whereColumn('paid_amount', '<', 'invoicedue')
            ->getDateCondition($dateFrom, $dateTo, $dateType)->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @return Order
     */
    public function getPaidForDeliveryCosReport($dateFrom, $dateTo, $dateType)
    {
        return Order::select(\DB::raw(
            "COUNT(appr_order.id) as total_orders,
            SUM(split_amount) as total_amount"
        ))->where('status', Order::STATUS_APPRAISAL_COMPLETED)
            ->whereNotNull('appr_paid')
            ->getDateCondition($dateFrom, $dateTo, $dateType)->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @return mixed
     */
    public function getUnpaidForDeliveryCosReport($dateFrom, $dateTo, $dateType)
    {
        return Order::select(\DB::raw(
            "COUNT(id) as total_orders,
            SUM(split_amount) as total_amount"
        ))->whereNull('appr_paid')
            ->where('status', Order::STATUS_APPRAISAL_COMPLETED)
            ->getDateCondition($dateFrom, $dateTo, $dateType)->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @return mixed
     */
    public function getOverallForDeliveryCosReport($dateFrom, $dateTo, $dateType)
    {
        return Order::select(\DB::raw(
            "COUNT(id) as total_orders,
            SUM(split_amount) as total_amount"
        ))->where('status', Order::STATUS_APPRAISAL_COMPLETED)
            ->getDateCondition($dateFrom, $dateTo, $dateType)->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getCompleteForCheckPaymentsReport($dateFrom, $dateTo)
    {
        return Order::select(\DB::raw(
            "COUNT(id) as total_orders,
            SUM(split_amount) as total_amount"
        ))->where('status', Order::STATUS_APPRAISAL_COMPLETED)
            ->whereBetween('appr_paid', [
                date('Y-m-d H:i:s', strtotime($dateFrom)),
                date('Y-m-d H:i:s', strtotime($dateTo)),
            ])->first();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getInProgressForCheckPaymentsReport($dateFrom, $dateTo)
    {
        return Order::select(\DB::raw(
            "COUNT(id) as total_orders,
            SUM(split_amount) as total_amount"
        ))->whereNotIn('status', [
            Order::STATUS_APPRAISAL_COMPLETED,
            Order::STATUS_TEMP,
            Order::STATUS_CANCELLED,
            Order::STATUS_AWAITING_CLIENT_APPROVAL
        ])->whereBetween('appr_paid', [
            date('Y-m-d H:i:s', strtotime($dateFrom)),
            date('Y-m-d H:i:s', strtotime($dateTo)),
        ])->first();
    }

    /**
     * @param $filter
     * @return array
     */
    public function getReconsiderationPipeline($filter)
    {
        $list = [];
        $orders = $this->order->where('status', 18)->orderBy('review_dts', 'DESC')->get();

        if ($orders) {
            if ($filter == 'UnderReview') {
                foreach ($orders as $order) {
                    $currentRevision = ReconsiderationLog::getLogCount($order->id, $order->revision);
                    $order->last_log = OrderLog::getLastLogEntryDate($order->id);
                    $list[] = $order;
                }
            } elseif ($filter == 'WaitingForApproval') {
                foreach ($orders as $order) {
                    $currentRevision = ReconsiderationLog::getLogCount($order->id, $order->revision);
                    if ($currentRevision > 0) {
                        continue;
                    }
                    $order->last_log = OrderLog::getLastLogEntryDate($order->id);
                    $list[] = $order;
                }
            }
        }
        return $list;
    }

    /**
     * @param $userId
     * @return int
     */
    public function getOrdersAcceptedByUserId($userId)
    {
        return Order::where('acceptedby', $userId)
            ->where('status', '!=', self::TEMPORDER)->count();
    }

    /**
     * @param $userId
     * @return int
     */
    public function getApprOrdersCompletedByUserId($userId)
    {
        return Order::where('acceptedby', $userId)
            ->whereIn('status', [
                Order::STATUS_APPRAISAL_COMPLETED,
                Order::STATUS_HOLD_UW_CONDITIONS,
                Order::STATUS_HOLD_UW_APPROVAL
            ])->count();
    }

    /**
     * @param $userId
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getOrdersAcceptedByUserIdWithLimit($userId, $limit = 10)
    {
        return Order::where('acceptedby', $userId)
            ->where('status', '!=', self::TEMPORDER)->paginate($limit);
    }

    /**
     * @param $userId
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUserSubmittedOrderswithLimit($userId, $limit=10)
    {
        return Order::where('orderedby', $userId)
            ->orderBy('ordereddate')->paginate($limit);
    }

    /**
     * @param $userId
     * @return int
     */
    public function getApprOrdersCompletedByUserIdWithDeliveredDate($userId)
    {
        return Order::where('acceptedby', $userId)
            ->whereIn('status', [
                Order::STATUS_APPRAISAL_COMPLETED,
                Order::STATUS_HOLD_UW_CONDITIONS,
                Order::STATUS_HOLD_UW_APPROVAL
            ])->whereNotNull('date_delivered')->count();
    }

    /**
     * @param $userId
     * @return int
     */
    public function getOrdersAcceptedByUserIdWithDeliveredDate($userId)
    {
        return Order::where('acceptedby', $userId)
            ->where('status', '!=', self::TEMPORDER)
            ->whereNotNull('date_delivered')->count();
    }

    /**
     * @param $userId
     * @return int
     */
    public function getOrdersPlacedByUserId($userId)
    {
        return Order::where('orderedby', $userId)
            ->whereNotIn('status', [
                Order::TEMP_STATUS,
                Order::STATUS_CANCELLED,
                Order::STATUS_CANCELLED_TRIP_FEE
            ])->count();
    }

    /**
     * @param $userId
     * @return int
     */
    public function getOrdersCompletedByUserId($userId)
    {
        return Order::where('orderedby', $userId)
            ->whereIn('status', [
                Order::STATUS_APPRAISAL_COMPLETED,
                Order::STATUS_HOLD_UW_CONDITIONS,
                Order::STATUS_HOLD_UW_APPROVAL,
            ])->count();
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAppraiserOrderTypeBreakdown($userId)
    {
        return Order::with('appraisalType')
            ->where('acceptedby', $userId)
            ->where('status', Order::STATUS_APPRAISAL_COMPLETED)
            ->where('appr_type', '>', 0)
            ->groupBy('appr_type')
            ->get();
    }

    /**
     * @param $userId
     * @param int $days
     * @return int
     */
    public function getAppraiserOrdersTurnOut($userId, $days=7)
    {
        return Order::whereRaw(
            "submitted<=accepteddate + INTERVAL " . $days . " DAY"
        )->where('status', Order::STATUS_APPRAISAL_COMPLETED)
            ->whereIn('appr_type', [
                Order::APPR_TYPE_FULL,
                Order::APPR_TYPE_CONDO,
                Order::APPR_TYPE_MANF,
                Order::APPR_TYPE_FULL_EPP,
                Order::APPR_TYPE_CONDO_EPP,
                Order::APPR_TYPE_MANF_EPP,
                Order::APPR_TYPE_BUILD_TASK,
                Order::APPR_TYPE_CONDO_BTF,
                Order::APPR_TYPE_MANF_BTF,
            ])->where('acceptedby', $userId)->count();
    }

    /**
     * @param $userId
     * @param $dateFrom
     * @param $dateTo
     * @return \Illuminate\Database\Eloquent\Collection|Collection|static[]
     */
    public function getByAcceptedUserAndDeliveredDate($userId, $dateFrom, $dateTo)
    {
        if($dateFrom && $dateTo) {
            return Order::where('acceptedby', $userId)
                ->whereBetween('date_delivered', [
                    $dateFrom,
                    $dateTo
                ])->get();
        }
        return Order::where('acceptedby', $userId)
            ->whereNotNull('date_delivered')->get();
    }

    /**
     * @param $apprId
     * @param $dateFrom
     * @param $dateTo
     * @return int
     */
    public function getAppraiserTotalCompletedFilesByDateRange($apprId, $dateFrom, $dateTo)
    {
        return Order::where('status', Order::STATUS_APPRAISAL_COMPLETED)
            ->whereBetween('date_delivered', [
                $dateFrom,
                $dateTo
            ])->where('acceptedby', $apprId)
            ->count();
    }

    /**
     * @param $apprId
     * @return int
     */
    public function getAppraiserTotalCompletedFullFiles($apprId)
    {
        return Order::whereIn('status', [
            Order::STATUS_APPRAISAL_COMPLETED,
            Order::STATUS_HOLD_UW_CONDITIONS,
            Order::STATUS_HOLD_UW_APPROVAL
        ])->whereNotNull('date_delivered')
            ->where('acceptedby', $apprId)
            ->whereIn('appr_type', [
                Order::APPR_TYPE_FULL,
                Order::APPR_TYPE_CONDO,
                Order::APPR_TYPE_MANF,
                Order::APPR_TYPE_FULL_EPP,
                Order::APPR_TYPE_CONDO_EPP,
                Order::APPR_TYPE_MANF_EPP,
                Order::APPR_TYPE_BUILD_TASK,
                Order::APPR_TYPE_CONDO_BTF,
                Order::APPR_TYPE_MANF_BTF
            ])
            ->count();
    }

    /**
     * @param array $statuses
     * @param $dateFrom
     * @param $dateTo
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getByAcceptedUserDeliveredRangeAndStatuses($statuses = [], $dateFrom, $dateTo, $userId)
    {
        return Order::whereIn('status', $statuses)
            ->where('acceptedby', $userId)
            ->whereBetween('date_delivered', [
                $dateFrom,
                $dateTo,
            ])->get();
    }

    /**
     * @param $apprId
     * @return \Illuminate\Database\Eloquent\Collection|Collection|static[]
     */
    public function getAppraiserTotalCompleted($apprId)
    {
        return Order::whereIn('status', [
            Order::STATUS_APPRAISAL_COMPLETED,
            Order::STATUS_HOLD_UW_CONDITIONS,
            Order::STATUS_HOLD_UW_APPROVAL
        ])->whereNotNull('date_delivered')
            ->where('acceptedby', $apprId)
            ->whereIn('appr_type', [
                Order::APPR_TYPE_FULL,
                Order::APPR_TYPE_CONDO,
                Order::APPR_TYPE_MANF,
                Order::APPR_TYPE_FULL_EPP,
                Order::APPR_TYPE_CONDO_EPP,
                Order::APPR_TYPE_MANF_EPP,
                Order::APPR_TYPE_BUILD_TASK,
                Order::APPR_TYPE_CONDO_BTF,
                Order::APPR_TYPE_MANF_BTF
            ])
            ->get();
    }

    /**
     * @param $filter
     * @return array
     */
    public function getOrdersByGroups($fromDate, $toDate, $dateType, $userId)
    {
        $statuses = [];

        // 120628
        switch ($dateType) {
            case 'ordereddate':


                    $orders = $this->order->select('appr_order.*')
                                            ->leftJoin('user as u','appr_order.orderedby', '=', 'u.id')
                                            ->where('u.id', '120628')
                                            // ->whereColumn('paid_amount', '>=', 'invoicedue')
                                            // ->where($dateType, '>=', $fromDate)
                                            // ->where($dateType, '<=', $toDate)
                                            // ->whereNotIn('status', [10, 20, 9])
                                            ->whereHas('groupData');

               break;
            case 'date_delivered':

                    $orders = $this->order->select('appr_order.*')
                                            ->leftJoin('user as u','appr_order.orderedby', '=', 'u.id')
                                            ->where('u.id', '120628')
                                            // ->whereColumn('paid_amount', '>=', 'invoicedue')
                                            // ->where($dateType, '>=', $fromDate)
                                            // ->where($dateType, '<=', $toDate)
                                            // ->whereIn('status', [6, 14, 17, 18])
                                            ->whereHas('groupData');
               break;
       }

       return $orders->get();
    }

    /**
     *
     * @return collection
     */
    public function statusSelectOrders()
    {

        $orders = $this->order;

        $settingType = $this->settingRepo->getSettingsByKey('status_select_appr_types');

        if ($settingType) {
            $orders =  $completedOrderInvoiceAmount->where('appr_type', $settingType);
        }

        $settingStatus = $this->settingRepo->getSettingsByKey('status_select_statuses');

        if ($settingStatus) {
            $orders =  $orders->where('status', $settingStatus);
        }

        // $orders->whereHas('adminTeamClient', function ($query) {
        //     $query->whereHas('adminTeam', function ($q) {
        //         $q->where('is_in_status_select', '1');
        //     });
        // });

        return $orders;
    }


    public function getOrderGeneratorReposrts($datetype, $dateFrom, $dateTo, $client, $lender, $type, $state, $status, $isClientApproval, $appraiserId)
    {

        $order = $this->order;
        $dateFrom =  $dateFrom. ' 00:00:00';
        $dateTo = $dateTo. ' 23:59:59';

        // date type
        switch ($datetype) {
            case 'date_ordered':
                $order = $order->whereBetween('ordereddate', [ $dateFrom, $dateTo]);
                break;
            case 'date_accepted':
                $order = $order->whereBetween('accepteddate', [ $dateFrom, $dateTo]);
                break;
            case 'date_delivered':
                $order = $order->whereBetween('date_delivered', [ $dateFrom, $dateTo]);
                break;
            case 'date_canceled':
                $order = $order->whereBetween('date_canceled', [ $dateFrom, $dateTo]);
                break;
            case 'date_uw_received':
                $order = $order->whereBetween('date_uw_received', [ $dateFrom, $dateTo]);
                break;
            case 'date_uw_completed':
                $order = $order->whereBetween('date_uw_completed', [ $dateFrom, $dateTo]);
                break;
            case 'date_first_paid':
                $order = $order->whereBetween('date_first_paid', [ $dateFrom, $dateTo]);
                break;
            case 'appraiser_paid':
                $order = $order->whereHas('appraisalPayment', function ($query) use ($dateFrom, $dateTo) {
                    $query->whereBetween('date_sent', [ $dateFrom, $dateTo]);
                });
                break;
        }

        // client
        if ($client) {
            $order = $order->whereIn('groupid', $client);
        }

        //  lender
        if ($lender) {
            $order = $order->whereIn('lender_id', $lender);
        }

        // types
        if ($type && count($type)) {
            $order = $order->whereIn('appr_type', $type);
        }

        // State
        if ($state && count($state)) {

            foreach ($state as $i) {
                $_states[$i] = strtolower($i);
            }

            $order = $order->whereIn('propstate', $_states);
        }

        // Status
        if ($status && $status != 'all' && $status != 'activecompleted' && $status != 'g$' && $status != 'revenue' && $status != 'unearned_revenue') {
            $order = $order->where('status', $status);
        } elseif ($status != 'all' && $status != 'activecompleted' && $status != 'g$' && $status != 'revenue' && $status != 'unearned_revenue') {
            $order = $order->whereNotIn('status', [6,9,10,20,25]);
        } elseif ($status == 'activecompleted') {
            $order = $order->whereNotIn('status', [9,10,20,25]);
        } elseif ($status == 'all') {
            $order = $order->whereNotIn('status', [9]);
            $conditions[] = "a.status NOT IN (9)";
        } elseif ($status == 'revenue') {
            $order = $order->whereNotIn('status', [9, 10]);
            $conditions[] = "a.status NOT IN (9,10)";
        } elseif ($status == 'unearned_revenue') {
            $order = $order->whereIn('status', [7,8,21,2,3,4,5,12]);
        } elseif ($status == 'g$') {
            $order = $order->whereIn('status', [6,10,14,17,18,20])->where('paid_amount', '!=', 'invoicedue');
        }

        // isClientApproval
        if ($isClientApproval) {

            if ($isClientApproval == 'Y') {
                $order = $order->where('is_client_approval', true);
            } elseif ($isClientApproval == 'N') {
                $order = $order->where('is_client_approval', false);
            }
        }

        if ($appraiserId) {
            $order = $order->where('acceptedby', $appraiserId);
        }

        return $order;

    }


    /**
     * @param $apprId
     * @param $time
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function completedOrders($apprId, $time)
    {
        return Order::whereIn('status', [
            Order::STATUS_APPRAISAL_COMPLETED,
            Order::STATUS_HOLD_UW_CONDITIONS,
            Order::STATUS_HOLD_UW_APPROVAL
        ])->where('date_delivered', '>=', date('Y-m-d H:i:s', $time))
            ->where('acceptedby', $apprId)
            ->whereIn('appr_type', [
                Order::APPR_TYPE_FULL,
                Order::APPR_TYPE_CONDO,
                Order::APPR_TYPE_MANF,
                Order::APPR_TYPE_FULL_EPP,
                Order::APPR_TYPE_CONDO_EPP,
                Order::APPR_TYPE_MANF_EPP,
                Order::APPR_TYPE_BUILD_TASK,
                Order::APPR_TYPE_CONDO_BTF,
                Order::APPR_TYPE_MANF_BTF
            ])->get();
    }

    public function updateByOrderedBy($id, $data)
    {
        return Order::where('orderedby', $id)->update($data);
    }

}
