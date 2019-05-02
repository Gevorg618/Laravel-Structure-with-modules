<?php

namespace Modules\Admin\Repositories\ManagerReport;

use Yajra\DataTables\Datatables;
use App\Models\Customizations\Status;
use App\Models\Management\AdminTeamsManager\AdminTeam;
use App\Models\Customizations\Type;
use Modules\Admin\Repositories\Geo\StatesRepository;
use Modules\Admin\Repositories\Management\WholesaleLenders\LendersRepository;
use Modules\Admin\Repositories\Users\UserRepository;
use App\Models\Appraisal\OrderLog;
use App\Models\Appraisal\Order;
use  Modules\Admin\Repositories\Ticket\OrderRepository;

class GeneratorRepository
{   

	public  $orderRepo;

	public function __construct()
	{
		$this->orderRepo = new OrderRepository();
	}

	/**
    * get date types 
    *
    * @return array
    */
	public function dateTypes(): array
	{
		return [
			'date_ordered' => 'Ordered',
			'date_accepted' => 'Accepted',
			'date_delivered' => 'Delivered',
			'date_canceled' => 'Canceled',
			'date_uw_received' => 'UW Received',
			'date_uw_completed' => 'UW Completed',
			'date_first_paid' => 'Date First Paid',
			'appraiser_paid' => 'Appraiser Paid'
		];
	}

	/**
    * get manager headers 
    *
    * @return array
    */
	public function getManagerReportHeaders(): array
	{
	    $items = [
	        'date_ordered' => 'Date Ordered',
	        'id' => 'Order ID',
	        'client' => 'Client Name',
	        'orderedby' => 'Ordered By',
	        'address' => 'Address',
	        'city' => 'City',
	        'county' => 'County',
	        'state' => 'State',
	        'zip' => 'Zip',
	        'borrower' => 'Borrower',
	        'loanrefnum' => 'Loan Reference Number',
	        'fha_case' => 'FHA Case Number',
	        'appr_type' => 'Appraisal Type',
	        'loan_type' => 'Loan Type',
	        'loan_purpose' => 'Loan Purpose',
	        'lender' => 'Lender',
	        'lender_id' => 'Wholesale Lender',
	        'payment_status' => 'Payment Status',
	        'invoice_amount' => 'Invoice Amount',
	        'client_pricing_amount' => 'Client Pricing Amount',
	        'paid_amount' => 'Paid Amount',
	        'final_appraisal_borrower_sendtopostalmail_amount' => 'DocuVault Mailing Fee',
	        'mail_paid_amount' => 'DocuVault Mailing Fee Paid Amount',
	        'appr' => 'Appraiser',
	        'appremail' => 'Appraiser Email',
	        'is_priority_appr' => 'Priority Appraiser',
	        'split_amount' => 'Split Amount',
	        'software_fee' => 'Software Fee',
	        'margin' => 'Margin',
	        'balancedue' => 'Balance Due',
	        'engager' => 'Engager',
	        'team' => 'Team',
	        'api_user' => 'Order Source',
	        'is_mercury' => 'Is Mercury',
	        'is_valutrac' => 'Is ValuTrac',
	        'is_fnc' => 'Is FNC',
	        'status' => 'Status',
	        'extended_turntime' => 'Extended Turn Time',
	        'mortgage_associate' => 'Mortgage Associate',
	        'date_ordered2' => 'Date Ordered',
	        'date_assigned' => 'Date Assigned',
	        'date_scheduled' => 'Date of Inspection',
	        'date_completed' => 'Date Submitted',
	        'date_inspection_completed' => 'Date Inspection Completed',
	        'date_delivered' => 'Date Delivered',
	        'date_canceled' => 'Date Canceled',
	        'date_first_paid' => 'First Payment Date',
	        'due_date' => 'Due Date',
	        'client_due_date' => 'Client Due Date',
	        'date_scheduled_set' => 'Date Scheduled',
	        'date_rescheduled' => 'Date Re-Scheduled',
	        'scheduled_turn_time' => 'Scheduled Turn Time',
	        'total_turn_time' => 'Total Turn Time',
	        'adjusted_turn_time' => 'Adjusted Turn Time',
	        'date_uw_received' => 'Date UW Received',
	        'date_uw_completed' => 'Date UW Completed',
	        'uw_total_submissions' => 'UW Submissions',
	        'qc_total_submissions' => 'QC Submissions',
	        'qc_total_turn_time' => 'QC Total Turn Time',
	        'uw_total_turn_time' => 'UW Total Turn Time',
	        'vendor_fha_license' => 'Appraiser FHA License',
	        'vendor_asc_license' => 'Appraiser ASC License',
	        'ucdp_risk_score' => 'UCDP Risk Score',
	        'last_log' => 'Last Log Date',
	        'last_log_entry' => 'Last Log Entry',
	        'appr_date_paid' => 'Appraiser Payment Date',
	        'appr_check_number' => 'Appraiser Check Number',
	        'appr_check_amount' => 'Appraiser Check Amount',
	        'appr_distance' => 'Appraiser Proximity',
	        'appr_license_type' => 'Appraiser License Type',
	        'payment_date' => 'Last Payment Date',
	        'payment_type' => 'Payment Type',
	        'delay_codes' => 'Delay Codes',
	        'refer' => 'Referrer',
	        'pricing_version' => 'Client Pricing Version',
	        'order_pricing_version' => 'Order Pricing Version',
	        'final_appraised_value' => 'Final Appraised Value',
	        // 'targus_value' => 'Targus Value',
	        'estimated_value' => 'Estimated Value',
	        'is_rush' => 'Rush Request',
	        'appraisal_software' => 'Appraisal Softeware',
	        'order_escalated' => 'Order Escalated',
	        'is_client_approval' => 'Client Approval',
	        'client_approval_reason' => 'Client Approval Reason',
	        'group_created_date' => 'Date Client Joined',
	        'sales_person_ae' => 'Sales Person (AE)',
	        'sales_person_sdr' => 'Sales Person (SDR)',
	        'sales_person_manager' => 'Sales Person (Manager)',
	        'cc_refund_reason' => 'CC Refund Reason',
	        'check_refund_reason' => 'Check Refund Reason',
	        'addendas' => 'Addendas',
	        'client_order_number' => 'Client Order Number',
	    ];

	    // Add active qc data collection items
	    $qcDataRows = \DB::select('SELECT * FROM appr_qc_data_collection_question ORDER BY pos ASC');

	    if ($qcDataRows) {
	        foreach ($qcDataRows as $r) {
	            $items['qc.data.' . $r->id] = $r->title;
	        }
	    }

	    return $items;
	}

	/**
    * get statuses 
    *
    * @return array
    */
	public function getStatuses(): array
	{
		$status = array('activecompleted' => '-- All Active Include Completed --', 'all' => '-- All --', 'revenue' => '-- Revenue --', 'unearned_revenue' => '-- Unearned Revenue --', 'g$' => ( getUserId() == '110944' ? '-- G$ --' : '-- Statement --' ) );
		$statuses = Status::getStatuses()->pluck('descrip', 'id');
		
		foreach($statuses as $k => $v) {
			$status[$k] = $v;
		}

		return $status;
	}

	/**
    * get  teams
    *
    * @return array
    */
	public function getTeams():object
	{
		return  AdminTeam::getAdminTeams()->pluck('team_title', 'id');
	}

	/**
    * get  apprasial types
    *
    * @return array
    */
	public function allTypes():array
	{
		$rows = Type::orderBy(\DB::raw('CONCAT(form,"",descrip)'), 'ASC')->get();
		$types = [];
		foreach($rows as $row) {
			$types[$row->id] = $row->form ? ($row->form . ' - ' . $row->descrip) : $row->descrip;
		}
		return $types;
	}

	public function getTaskMinutes()
	{
	    $m = ['-1' => 'Every Minute'];
	    for ($i = 0 ; $i < 60; $i++) {
	        $m[$i] = $i;
	    }
	    return $m;
	}

	public function getTaskHours()
	{
	    $m = ['-1' => 'Every Hour', '0' => 'Midnight'];
	    for ($i = 1 ; $i < 24; $i++) {
	        if ($i < 12) {
	            $ampm = $i. 'am';
	        } elseif ($i == 12) {
	            $ampm = 'Midday';
	        } else {
	            $ampm = $i - 12 . 'pm';
	        }

	        $m[$i] = $i. ' - ('.$ampm.')';
	    }
	    return $m;
	}


	public function getTaskWeekDays()
	{
	    $m = [
	        '-1' => 'Every Weekday',
	        '0' => 'Sunday',
	        '1' => 'Monday',
	        '2' => 'Tuesday',
	        '3' => 'Wednesday',
	        '4' => 'Thursday',
	        '5' => 'Friday',
	        '6' => 'Saturday'
	    ];
	    
	    return $m;
	}

	public function getTaskMonthDays()
	{
	    $m   = ['-1' => 'Every Day of the month'];
	    for ($i = 1 ; $i < 32; $i++) {
	        $m[$i] = $i;
	    }
	    return $m;
	}

	/*
	 *
	 *
	 * 
	 */
	public function getStates():object
	{	 
		$statesRepo = new StatesRepository;
		$states = $statesRepo->getStates()->pluck('state', 'abbr');
		return $states;
	}

	public function searchAppr($name)
	{

		$limit =  20;
	
		$name = trim($name);

		$where = "(CONCAT(o.firstname,' ',o.lastname) LIKE '%".$name."%' OR u.email LIKE '%".$name."%') AND u.user_type=4 AND u.active='Y'";
		$users = \DB::select("SELECT u.id, u.email, o.firstname, o.lastname FROM user u LEFT JOIN user_data o ON (u.id=o.user_id) WHERE {$where} ORDER BY o.firstname ASC LIMIT 20");

		$rows = [];
		$i = 0;

		if($users && count($users)) {
			foreach($users as $user) {
				
				$name = trim($user->firstname . ' ' . $user->lastname);
				
				if(!$name) {
					continue;
				}

				$rows[] = ['label' => $name . ( ' ('.$user->email.')' ), 'value' => $user->id];
			}
		}

		return $rows;
	}

	public function distance($lat1, $lon1, $lat2, $lon2, $unit) {

	  $theta = $lon1 - $lon2;
	  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	  $dist = acos($dist);
	  $dist = rad2deg($dist);
	  $miles = $dist * 60 * 1.1515;
	  $unit = strtoupper($unit);

	  if ($unit == "K") {
	    return ($miles * 1.609344);
	  } else if ($unit == "N") {
	      return ($miles * 0.8684);
	    } else {
	        return $miles;
	      }
	}

	/*
	 *
	 * Get Items for download
	 */
	public function getItems($data)
	{
		$lenderRepo = new LendersRepository();

		$dateRange = explode("-", $data['daterange']);
        
        $dateFrom = date('Y-m-d', strtotime($dateRange[0]));
        $dateTo = date('Y-m-d', strtotime($dateRange[1]));
       	
	    $client = isset($data['client']) ? $data['client'] : null;
	    $lender = isset($data['lenders']) ? $data['lenders'] : null;
	    $type = isset($data['type']) ? $data['type'] : null;
	    $status = isset($data['status']) ? $data['status'] : null;
	    $columns = isset($data['columns']) ? $data['columns']  : null;
	    $datetype = isset($data['datetype']) ? $data['datetype'] : null;
	    $team = isset($data['team']) ? $data['team'] : null;
	    $state = isset($data['states']) ? $data['states'] : null;
	    $hideTotals = isset($data['hidetotals']) ? $data['hidetotals'] : false;
	    $isClientApproval = $data['is_client_approval'];
	    $appraiserId = isset($data['appr_id']) ?$data['appr_id'] : null ;
	    
	    $where = "";
	    $conditions = [];
	    $limit = "";
	    $items = [];

	    $totalInvoice = 0;
	    $totalPaid = 0;
	    $totalSplit = 0;
	    $totalMargin = 0;
	    $turnTimeAvg = 0;
	    $turnTimInMinutes = 0;
	    $turnTimInDays = 0;
	    $turnTimeOrders = 0;

	    $ordersCount = 0;

	    $defHeaders = $this->getManagerReportHeaders();
	    $visibleHeaders = [];
	    $usedHeaders = [];
	    
	    if ($columns && count($columns)) {
	        foreach ($defHeaders as $k => $v) {
	            if (in_array($k, $columns)) {
	                $visibleHeaders[$k] = $v;
	            }
	        }
	    }
	    
	    if (count($visibleHeaders)) {
	        $usedHeaders = $visibleHeaders;
	    } else {
	        $usedHeaders = $defHeaders;
	    }

	    $revertedUsedHeaders = array_flip($usedHeaders);

	    $lenderTitles = [];

	    // Get lenders list
	    if (in_array('lender_id', $revertedUsedHeaders)) {
	        $lenderRows = $lenderRepo->getWholesaleLenders();
	        if ($lenderRows) {
	            foreach ($lenderRows as $lenderRow) {
	                $lenderTitles[$lenderRow->id] = $lenderRow->lender;
	            }
	        }
	    }

	       
        $orders = $this->orderRepo->getOrderGeneratorReposrts($datetype, $dateFrom, $dateTo, $client, $lender, $type, $state, $status, $isClientApproval, $appraiserId)->limit(1500)->get();
        
        foreach ($orders as $order) {

            if (in_array('team', $revertedUsedHeaders) || $team) {
                $teamData = $this->getGroupTeamByUserId($order->orderedby);
            	
                if ($teamData && $team && $teamData->team_id != $team) {
                    continue;
                }
            }
            

            if (in_array('qc_total_turn_time', $revertedUsedHeaders) || in_array('uw_total_turn_time', $revertedUsedHeaders) || in_array('date_uw_received', $revertedUsedHeaders) || in_array('date_uw_completed', $revertedUsedHeaders)) {
                $dateDelivered = $order->date_delivered;
                $dateDeliveredUnix = $dateDelivered ? strtotime($dateDelivered) : '';

                $turnTime = $this->getOrderTurnTimeByDateDelivered($order, $dateDeliveredUnix);
                $dateUWCompleted = $order->date_uw_completed;
                $dateUWReceived = $order->date_uw_received;
                $uwTurnTime = $this->getOrderUWTurnTimeNew($order);
                $qcTurnTime = $this->getOrderQCTurnTimeNew($order);
                $scheduledTurnTime = '';
                if ($order->schd_date) {
                    $scheduledTurnTime = $this->getOrderTurnTimeByDateScheduled($order, $dateDeliveredUnix);
                }

                if ($uwTurnTime) {
                    $uwTurnTime = $this->getOrderTurnTimeInMinutesByTurnTimeString($uwTurnTime);
                }

                if ($qcTurnTime) {
                    $qcTurnTime = $this->getOrderTurnTimeInMinutesByTurnTimeString($qcTurnTime);
                }
            }
            
            $rows[$order->id] = [
                'date_ordered' => date('m/d/Y H:i', strtotime($order->ordereddate)),
                'id' => $order->id,
                'client' => $order->groupData? $order->groupData->descrip: 'N/A',
                'orderedby' => $order->getOrderedByUserAttribute(),
                'address' => $order->propaddress1,
                'city' => $order->propcity,
                'county' => in_array('county', $revertedUsedHeaders) && $order->zipCode ? $order->zipCode->county : '',
                'state' => strtoupper($order->propstate),
                'zip' => $order->propzip,
                'borrower' => $order->borrower,
                'loanrefnum' => $order->loanrefnum,
                'fha_case' => $order->fha_case,
                'appr_type' => $order->appr_form . ' ' . $order->appr_name,
                'loan_type' => in_array('loan_type', $revertedUsedHeaders) ? $order->loanType->descrip : '',
                'loan_purpose' => in_array('loan_purpose', $revertedUsedHeaders) && $order->loanReason ? $order->loanReason->descrip : '',
                'lender' => $order->lender,
                'lender_id' => in_array('lender_id', $revertedUsedHeaders) && isset($lenderTitles[$order->lender_id]) ? $lenderTitles[$order->lender_id] : '',
                'payment_status' => $order->getPaymentStatusAttribute(),
                'invoice_amount' => floatval($order->invoicedue),
                'client_pricing_amount' => in_array('client_pricing_amount', $revertedUsedHeaders) ? $this->getPrice($order) : '',
                'paid_amount' => floatval($order->paid_amount),
                'final_appraisal_borrower_sendtopostalmail_amount' => floatval($order->final_appraisal_borrower_sendtopostalmail_amount),
                'mail_paid_amount' => floatval($order->mail_paid_amount),
                'appr' => in_array('appr', $revertedUsedHeaders) && $order->userData ? $order->userData->firstname . ' ' . $order->userData->lastname : '',	                        
                'appremail' => in_array('appremail', $revertedUsedHeaders) && $order->client ? $order->client->email : '',
                'is_priority_appr' => in_array('is_priority_appr', $revertedUsedHeaders) && $order->userData ? ($order->userData->is_priority_appr ? 'Yes' : 'No') : '',
                'split_amount' => $order->split_amount,
                'software_fee' => $order->software_fee,
                'margin' => floatval($order->invoicedue - $order->split_amount),
                'balancedue' => floatval($order->invoicedue - $order->paid_amount),
                'engager' => in_array('engager', $revertedUsedHeaders) && $order->userDataByAssigned ? $order->userDataByAssigned->firstname . ' ' .$order->userDataByAssigned->lastname : 'N/A',
                'team' => (in_array('team', $revertedUsedHeaders) && isset($teamData->team_title) ? $teamData->team_title : 'N/A'),
                'api_user' => (in_array('api_user', $revertedUsedHeaders) ? $this->getSource($order) : 'N/A'),
                'is_mercury' => (in_array('is_mercury', $revertedUsedHeaders) ? ($order->is_mercury ? 'Yes' : 'No') : 'N/A'),
                'is_valutrac' => (in_array('is_valutrac', $revertedUsedHeaders) ? ($order->is_valutrac ? 'Yes' : 'No') : 'N/A'),
                'is_fnc' => (in_array('is_fnc', $revertedUsedHeaders) ? ($order->is_fnc ? 'Yes' : 'No') : 'N/A'),
                'status' => in_array('status', $revertedUsedHeaders) && $order->apprStatus->first() ? $order->apprStatus->first()->descrip : '',
                'extended_turntime' => (in_array('extended_turntime', $revertedUsedHeaders) ? ($order->extended_turntime ? 'Yes' : 'No') : 'N/A'),
                'mortgage_associate' => $order->mortgage_associate,
                'date_ordered2' => date('m/d/Y H:i', strtotime($order->ordereddate)),
                'date_assigned' => $order->accepteddate ? date('m/d/Y H:i', strtotime($order->accepteddate)) : '',
                'date_scheduled' => $order->schd_date ? date('m/d/Y H:i', strtotime($order->schd_date)) : '',
                'date_completed' => $order->submitted ? date('m/d/Y H:i', strtotime($order->submitted)) : '',
                'date_inspection_completed' => $order->completed ? date('m/d/Y H:i', strtotime($order->completed)) : '',
                'date_delivered' => $order->date_delivered ? date('m/d/Y H:i', strtotime($order->date_delivered)) : '',
                'date_canceled' => $order->date_canceled ? date('m/d/Y H:i', strtotime($order->date_canceled)) : '',
                'date_first_paid' => $order->date_first_paid ? date('m/d/Y H:i', strtotime($order->date_first_paid)) : '',
                'due_date' => $order->due_date ? date('m/d/Y', $order->due_date) : '',
                'client_due_date' => $order->client_due_date ? date('m/d/Y', $order->client_due_date) : '',
                'date_scheduled_set' => $order->date_scheduled ? date('m/d/Y G:i A', $order->date_scheduled) : '',
                'date_rescheduled' => $order->date_rescheduled ? date('m/d/Y G:i A', $order->date_rescheduled) : '',
                'scheduled_turn_time' => isset($scheduledTurnTime) ? $scheduledTurnTime : '',
                'total_turn_time' => isset($turnTime) ? $turnTime: '',
                'adjusted_turn_time' => $this->getOrderAdjustedTurnTime($order),
                'date_uw_received' => isset($dateUWReceived) ? $dateUWReceived: '',
                'date_uw_completed' =>isset($dateUWCompleted) ? $dateUWCompleted : '',
                'uw_total_submissions' => in_array('uw_total_submissions', $revertedUsedHeaders) && $order->apprUw ? $order->apprUw->count()  : '',
                'qc_total_submissions' => in_array('qc_total_submissions', $revertedUsedHeaders) && $order->qcStats ? $order->qcStats->count() : '',
                'qc_total_turn_time' => isset($qcTurnTime) ? $qcTurnTime: '',
                'uw_total_turn_time' => isset($uwTurnTime) ? $uwTurnTime : '',
                'vendor_fha_license' => in_array('vendor_fha_license', $revertedUsedHeaders) ? $this->getReportAppraiserFhaLicense($order->acceptedby, $order->propstate) : '',
                'vendor_asc_license' => in_array('vendor_asc_license', $revertedUsedHeaders) ? $this->getReportAppraiserAscLicense($order->acceptedby, $order->propstate) : '',
                'ucdp_risk_score' => $order->apprOrderUCDP ? $order->apprOrderUCDP->risk_score : '',
                'last_log' => in_array('last_log', $revertedUsedHeaders) ? OrderLog::getLastLogEntryDate($order->id) : '',
                'last_log_entry' => in_array('last_log_entry', $revertedUsedHeaders) && $order->lastLog->first() ? $order->lastLog->first()->info : '',
                'appr_date_paid' => in_array('appr_date_paid', $revertedUsedHeaders) && $order->appraisalPayment ?  date('m/d/Y', strtotime($order->appraisalPayment->date_sent)) : '' ,
                'appr_check_number' => in_array('appr_check_number', $revertedUsedHeaders) && $order->appraisalPayment ?  date('m/d/Y', strtotime($order->appraisalPayment->checknum)) : '' ,
                'appr_check_amount' => in_array('appr_check_amount', $revertedUsedHeaders) && $order->appraisalPayment ?  date('m/d/Y', strtotime($order->appraisalPayment->checkamount)) : '' ,
                'appr_distance' => in_array('appr_distance', $revertedUsedHeaders) ? $this->getOrderProximityToAppraiser($order) : '',
                'appr_license_type' => in_array('appr_license_type', $revertedUsedHeaders) ? '' : '',
                'payment_date' => in_array('payment_date', $revertedUsedHeaders) ? $this->getOrderPaymentLastDate($order->id) : '',
                'payment_type' => in_array('payment_type', $revertedUsedHeaders) ? $this->getOrderPaymentType($order->id) : '',
                'delay_codes' => in_array('delay_codes', $revertedUsedHeaders) ? $this->getOrderDelayCodeNames($order) : '',
                'refer' => in_array('refer', $revertedUsedHeaders) ? $order->refer : '',
                'pricing_version' => in_array('pricing_version', $revertedUsedHeaders) ? ($this->getClientHasCustomPricing($order->groupid) ? 'Custom Pricing' : $this->getUserGroupPricingVersion($order->group_id)) : '',
                'order_pricing_version' => in_array('order_pricing_version', $revertedUsedHeaders) ? $this->getPricingVersionTitle($order) : '',
                'final_appraised_value' => in_array('final_appraised_value', $revertedUsedHeaders) ? str_replace(array('$', ','), '', $order->final_appraised_value) : '',
                'estimated_value' => $order->estimated_value,
                'is_rush' => $order->is_rush ? 'Yes' : 'No',
                'appraisal_software' => in_array('appraisal_software', $revertedUsedHeaders) && $order->approrderXmlInfo ? $order->approrderXmlInfo->softwarename : '',
                'order_escalated' => $order->mgrescalate == 'Y' ? 'Yes' : 'No',
                'is_client_approval' => $order->is_client_approval ? 'Yes' : 'No',
                'client_approval_reason' => $order->client_approval_reason,
                'group_created_date' => $order->group_created_date ? date('m/d/Y g:i A', $order->group_created_date) : '',
                'sales_person_ae' => in_array('sales_person_ae', $revertedUsedHeaders) ? $order->sales_person_ae : '',
                'sales_person_sdr' => in_array('sales_person_sdr', $revertedUsedHeaders) ? $order->sales_person_sdr : '',
                'sales_person_manager' => in_array('sales_person_manager', $revertedUsedHeaders) ? $order->sales_person_manager : '',
                'cc_refund_reason' => in_array('cc_refund_reason', $revertedUsedHeaders) ? $this->getLatestCreditCardRefundReason($order->id) : '',
                'check_refund_reason' => in_array('check_refund_reason', $revertedUsedHeaders) ? $this->getLatestCheckRefundReason($order->id) : '',
                'addendas' => in_array('addendas', $revertedUsedHeaders) ?  $order->getAddendasListAttribute() : '',
                'client_order_number' => in_array('client_order_number', $revertedUsedHeaders) ? $order->client_order_number : '',
            ];

            $qcDataRows  = \DB::select('SELECT * FROM appr_qc_data_collection_question ORDER BY pos ASC');

            if ($qcDataRows) 
            {
                foreach ($qcDataRows as $r) 
                {
                    $rows[$order->id]['qc.data.' . $r->id] = isset($qcDataAnswers[$r->id]) ? str_replace("\n", '', trim($qcDataAnswers[$r->id])) : '';
                }
            }
           
            if (in_array('qc_total_turn_time', $revertedUsedHeaders) || in_array('uw_total_turn_time', $revertedUsedHeaders)) {

                if ($turnTime) {
                    $rows[$order->id]['total_turn_time'] = $this->getOrderTurnTimeInMinutesByTurnTimeString($turnTime);
                    
                    $turnTimInDays += $rows[$order->id]['total_turn_time'];
                    $turnTimeOrders++;
                }
                
                if ($scheduledTurnTime) {
                    $rows[$order->id]['scheduled_turn_time'] = $this->getOrderTurnTimeInMinutesByTurnTimeString($scheduledTurnTime);
                }
            }
            
            $totalInvoice += floatval($order->invoicedue);

            $totalPaid += $order->paid_amount;
            $totalSplit += $order->split_amount;
            $totalMargin += ($order->invoicedue-$order->split_amount);

            $ordersCount++;
        }

        if(!isset($rows)) {
        	return false;
        }
	    foreach ($rows as $key => $value) 
	    {
	    	
	    	foreach ($usedHeaders as $keyHead => $valueHead) 
	    	{
	        	$dataCsv[$key][$valueHead] =  $value[$keyHead];
	        }
        }
       
    	return $dataCsv;
	}

	/**
	 * Get order answers
	 */
	public  function getAnswers($orderId) 
	{
		return \DB::select('SELECT a.value, q.format, q.id FROM appr_qc_data_collection_answer a LEFT JOIN appr_qc_data_collection_question q ON (q.id=a.question_id) WHERE a.order_id=:orderid');
	}

	/**
	 * Return answers as array
	 *
	 */
	public  function getAnswersList($orderId) {
		$rows = $this->getAnswers($orderId);
		$list = [];
		if($rows) {
			foreach($rows as $row) {
				$list[$row->id] = $row->value;
			}
		}

		return $list;
	}

	public function getLatestCheckRefundReason($id)
	{

	    $row = \DB::selectOne("SELECT * FROM appr_cim_check_payments WHERE order_id=:id AND is_visible=1 AND ref_type='REFUND' ORDER BY id DESC", [':id' => $id]);
	    if ($row) {
	        return $row->refund_reason;
	    }

	    return null;
	}
	public function getLatestCreditCardRefundReason($id)
	{

	    $row = \DB::selectOne("SELECT * FROM appr_fd_payments WHERE order_id=:id AND is_success=1 AND is_visible=1 AND ref_type='REFUND' ORDER BY id DESC", [':id' => $id]);
	    if ($row) {
	        return $row->refund_reason;
	    }

	    return null;
	}

	public  function getPricingVersionTitle($order)
    {

        if ($order->pricing_version == 0) {
            return 'Custom Pricing';
        }
        
        $version = $order->apprStatePricingVersion;
        if ($version) {
            return $version->title;
        }

        return 'N/A';
    }

	public function getUserGroupPricingVersion($groupId) 
	{
		global $db;
		$row = \DB::selectOne("SELECT pricing_version FROM user_groups WHERE id='".$groupId."'");
		if($row) {
			$version = $this->getPricingVersionById($row->pricing_version);
			if($version) {
				return $version->title;
			}
		}
		return '';
	}

	public function getPricingVersionById($id) 
	{
		return \DB::selectOne("SELECT * FROM appr_state_price_version WHERE id='".$id."'");		
	}

	public function getClientHasCustomPricing($id) 
	{
		$row = \DB::selectOne("SELECT COUNT(id) as total FROM appr_state_price WHERE groupid='".$id."'");
		return $row->total ? true : false;
	}
	public function getOrderDelayCodeTypes()
	{

		$items = [];
		$rows = \DB::select("SELECT * FROM appr_order_delay_code_type ORDER BY name ASC");
		foreach($rows as $row) {
			$items[$row->id] = $row->name;
		}
		return $items;
	}

	public function getOrderDelayCodeNames($order) 
	{

		$rows = $order->apprOrderDelayCode;
		$codes = $this->getOrderDelayCodeTypes();
		$items = [];

		if($rows) {
			foreach($rows as $row) {
				$items[] = $codes[$row->type_id];
			}
		}
		return implode('&', $items);
	}

	public function getOrderPaymentType($orderId) 
	{
		$isCheck = false;
		$isCredit = false;

		$credit = \DB::selectOne("SELECT * FROM appr_fd_payments WHERE order_id='".$orderId."' AND is_success=1 AND is_visible=1 AND ref_type='CHARGE' ORDER BY id DESC");
		if($credit) {
			$isCredit = true;
		}

		if(!$isCredit) {
			$credit = \DB::selectOne("SELECT * FROM appr_cim_payments WHERE order_id='".$orderId."' AND is_success=1 AND is_visible=1 AND ref_type='CHARGE' ORDER BY id DESC");
			if($credit) {
				$isCredit = true;
			}
		}

		$check = \DB::selectOne("SELECT * FROM appr_cim_check_payments WHERE order_id='".$orderId."' ORDER BY id DESC");
		if($check) {
			$isCheck = true;
		}

		if($isCheck && $isCredit) {
			return 'Both';
		} elseif($isCheck && !$isCredit) {
			return 'Check';
		} elseif($isCredit && !$isCheck) {
			return 'Credit Card';
		}

		return 'N/A';
	}

	public function getOrderPaymentLastDate($orderId) 
	{

		// First Data
		$fd = \DB::selectOne("SELECT * FROM appr_fd_payments WHERE order_id='".$orderId."' AND is_success=1 AND is_visible=1 AND ref_type='CHARGE' ORDER BY id DESC");

		// CIM
		$credit = \DB::selectOne("SELECT * FROM appr_cim_payments WHERE order_id='".$orderId."' AND is_success=1 AND is_visible=1 AND ref_type='CHARGE' ORDER BY id DESC");
		$check = \DB::selectOne("SELECT * FROM appr_cim_check_payments WHERE order_id='".$orderId."' ORDER BY id DESC");
		$date = null;
		if($fd) {
			$date = $fd->created_date;
		}

		if($credit) {
			$date = $credit->created_date;
		}

		if($check && $check->created_date > $date) {
			$date = $check->created_date;
		}

		return $date ? date('m/d/Y', $date) : '';
	}

	public function getOrderProximityToAppraiser($order) 
	{
		
		$appr = $order->userDataByAcceptedBy;
		
		// Approx distance set
		if($order->appr_distance > 0) {
			return $order->appr_distance;
		}
	 	if ($appr) {
	 		$km = $this->distance($appr->pos_lat, $appr->pos_long, $order->pos_lat,   $order->pos_long, "K");
	 		return round($km, 2);
	 	}

		return null;
	}

	public function getUserASCLicenses($userId)
	{

		$userRepo = new UserRepository();

		$user = $userRepo->getUserInfoById($userId);
		
		if(!$user) {
			return false;
		}

	    $licenses = \DB::select("SELECT license_number FROM user_asc_license WHERE user_id=:id", [':id' => $userId]);
	    $rows = false;

	    if($licenses) {
	        $list = [];
	        foreach($licenses as $lic) {
	            $list[] = $lic->license_number;
	        }

	        $rows = \DB::table('asc_data')->whereIn('lic_number', $list)->get();
	    }
	    
		if($rows) {
			return $rows;
		}

		return $rows;
	}

	public function getReportAppraiserAscLicense($id, $state)
	{
	    $rows = $this->getUserASCLicenses($id);
	    $result = '';
	    if ($rows) {
		    foreach($rows as $row) {
		        if(strtolower($row->state) == strtolower($state)) {
		            $result = $row->lic_number;
		            break;
		        }
		    }
		}
	    return $result;
	}

	public function getUserHUDLicenses($userId) 
	{
		$userRepo = new UserRepository();

		$user = $userRepo->getUserInfoById($userId);
		
		if(!$user) {
			return false;
		}

	    $licenses = \DB::select("SELECT license_number FROM user_fha_state_approved WHERE user_id=:id", [':id' => $userId]);
	    $rows = false;
	    
	    if($licenses) {
	        $list = [];
	        foreach($licenses as $lic) {
	            $list[] = $lic->license_number;
	        }

	        $rows = \DB::table('appr_fha_license')->whereIn('license_number', $list)->get();

	    }

		return $rows;
	}

	public function getReportAppraiserFhaLicense($id, $state)
	{
	    $rows = $this->getUserHUDLicenses($id);
	    $result = '';
	    if ($rows) {
	    	foreach($rows as $row) {
		        if(strtolower($row->state) == strtolower($state)) {
		            $result = $row->license_number;
		            break;
		        }
		    }

	    }
	    
	    return $result;
	}

	public function getOrderDateDeliveredTimeStamp($order)
	{

	    if ($order && $order->date_delivered) {
	        return strtotime($order->date_delivered);
	    }

	    $row = $order->lastLog->first();

	    if ($row) {
	        return strtotime($row->dts);
	    }

	    return '';
	}

	public function getOrderDelayedDates($orderId)
	{

	    $items = array();
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

	public function getOrderAdjustedTurnTime($order, $returnOriginal = false)
	{

	    $dateDeliveredUnix = $this->getOrderDateDeliveredTimeStamp($order);

	    $turnTime = $this->getOrderTurnTimeByDateDelivered($order, $dateDeliveredUnix);
	    $turnTime = $this->getOrderTurnTimeInMinutesByTurnTimeString($turnTime);

	    $delayedTimes = $this->getOrderDelayedDates($order->id);


	    if ($delayedTimes) {
	        foreach ($delayedTimes as $r) {
	            $turnTime -= $r['diff'];
	        }
	    }

	    if (!$returnOriginal && !count($delayedTimes)) {
	        return 0;
	    }

	    return $turnTime;
	}

	public  function getSource($order)
    {

        if ($order->apiUser) {
            return $order->apiUser->title;
        } elseif ($order->is_mercury) {
            return 'Mercury';
        } elseif ($order->is_valutrac) {
            return 'ValuTrac';
        } elseif ($order->is_fnc) {
            return 'FNC';
        } elseif ($order->refer != "") {
            return $order->refer;
        }

        return "Direct";
    }

	public function getGroupInfoById($id)
	{
	    return \DB::selectOne("SELECT * FROM user_groups WHERE id='" . $id . "'");
	}

	public  function appraisalCustomPricingVersion($id) 
	{
		return \DB::selectOne("SELECT * FROM appr_state_price WHERE groupid=:id", [':id' => $id]);
	}

	/**
	 * Get appraisal price from the pricing version based on
	 * - pricing version used + loan reason selected
	 * - if custom pricing make sure custom pricing exists and loan reason selected
	 * - otherwise use default pricing
	 * @return int
	 */
    public  function getPrice($order)
    {
        $group =  $order->groupid;
        
        $groupData = $this->getGroupInfoById($group);
        $price = null;
        $amount = 0;

        // If we have pricing set for the group
        if (($clientPricing = $this->appraisalCustomPricingVersion($group))) {

            // Match custom pricing with the loan reason
            // if we won't find anything we will try to load later on from any pricing version that matches the loan reason
            $price = \DB::selectOne("SELECT a.groupid, a.appr_type, a.amount, a.fha_amount, a.loan_type, l.loan_id FROM appr_state_price a LEFT JOIN appr_state_price_client_loan_reason l ON (a.groupid=l.client_id) WHERE a.groupid=:id AND a.appr_type=:atype AND a.state=:state AND l.loan_id=:loan AND (a.loan_type=:type OR a.loan_type=0)", [':atype' => $order->appr_type, ':id' => $group, ':state' => $order->propstate, ':loan' => $order->loanpurpose, ':type' => $order->loantype]);
        }


        // Order specific pricing version
        if (!$clientPricing && !$price && $order->pricing_version) {
            $price = \DB::selectOne(
          					"SELECT a.version_id, a.appr_type, a.amount, a.fha_amount, a.loan_type, l.loan_id 
                            FROM appr_state_price_version_row a 
                            LEFT JOIN appr_state_price_version_loan_reason l ON (a.version_id=l.version_id) 
                            WHERE a.version_id=:id 
                            AND a.appr_type=:atype 
                            AND a.state=:state 
                            AND (a.loan_type=:type OR a.loan_type=0)",
                             [
                                ':atype' => $order->appr_type,
                                ':id' => $order->pricing_version,
                                ':state' => $order->propstate,
                                // ':loan' => $order->loanpurpose,
                                ':type' => $order->loantype
                            ]
      		);
           
        }


        // No client pricing found so match the regular pricing
        if (!$clientPricing && !$price && $groupData) {


        		$price = \DB::selectOne(
	                "SELECT a.version_id, a.appr_type, a.amount, a.fha_amount, a.loan_type, l.loan_id 
	                                    FROM appr_state_price_version_row a 
	                                    LEFT JOIN appr_state_price_version_loan_reason l ON (a.version_id=l.version_id) 
	                                    WHERE a.version_id=:id 
	                                    AND a.appr_type=:atype 
	                                    AND a.state=:state 
	                                    AND (a.loan_type=:type OR a.loan_type=0)",
	                                    [
	                                        ':atype' => $order->appr_type,
	                                        ':id' => $groupData->pricing_version,
	                                        ':state' => $order->propstate,
	                                        // ':loan' => $order->loanpurpose,
	                                        ':type' => $order->loantype
	                                    ]
	            );
        }

        if (!$price) {

            // We couldn't find a match
            // most likely the loan reason is under TRID so try to load any version with that loan reason
            $price = \DB::selectOne(
                	"SELECT a.version_id, a.appr_type, a.amount, a.fha_amount, a.loan_type, l.loan_id 
                    FROM appr_state_price_version_row a 
                    LEFT JOIN appr_state_price_version_loan_reason l ON (a.version_id=l.version_id) 
                    WHERE a.state=:state 
                    AND a.appr_type=:atype 
                    AND (a.loan_type=:type OR a.loan_type=0)",
                    [
                        ':atype' => $order->appr_type,
                        ':state' => $order->propstate,
                        //':loan' => $order->loanpurpose,
                        ':type' => $order->loantype
                    ]
			);
        }

        // Figure out price
        if ($price) {
            $amount = $this->isFHA($order) ? $price->fha_amount : $price->amount;
        }

        if (!$amount) {

            // Load appraisal type base price
            $apprType = $this->appraisalOrderType($order->appr_type);

            if ($apprType) {
                $amount = $this->isFHA($order) ? $apprType->baseprice_fha : $apprType->baseprice_con;
            }
        }

        return $amount;
    }

    public function appraisalOrderType($id) 
    {
		return  \DB::selectOne("SELECT * FROM appr_type WHERE id=:id", [':id' => $id]);
	}

   	/**
     * Check if order is FHA
     */
    public function isFHA($order)
    {
        return (bool)(in_array($order->loantype, [2, 4]) || $order->req_fha == 'Y');
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

	public function getOrderTurnTimeByDateScheduled($order, $deliveredDate)
	{
	    if ($deliveredDate) {
	        //return dateDiffHours($order->schd_date, date('Y-m-d H:i', $deliveredDate));
	        $diff = $this->getTotalNumberOfDays(strtotime($order->schd_date), $deliveredDate);
	        return sprintf('0 Mo %s D %s H %s M', $diff['d'], $diff['h'], $diff['m']);
	    }
	    return '';
	}

	public function getOrderQCTurnTimeNew($order)
	{
	    $from = $order->submitted;
	    $to = $order->date_delivered;

	    if ($from && $from != "0000-00-00 00:00:00" && $to && $to != "0000-00-00 00:00:00") {
	        return $this->dateDiffHours($from, $to);
	    }
	    return '';
	}

	public function dateDiffHours($from, $to) {
		$diff = $this->getTotalNumberOfDays(strtotime($from), strtotime($to));
		return sprintf('0 Mo %s D %s H %s M', $diff['d'], $diff['h'], $diff['m']);
	}

	public function getOrderUWTurnTimeNew($order)
	{
	    $from = $order->date_uw_received;
	    $to = $order->date_uw_completed;

	    if ($from && $from != "0000-00-00 00:00:00" && $to && $to != "0000-00-00 00:00:00") {
	        return $this->dateDiffHours($from, $to);
	    }
	    return '';
	}

	/**
	 * Return team data based on user id -> group id
	 */
	public function getGroupTeamByUserId($userId) 
	{
	
		// Get user group
		$user = \DB::selectOne("SELECT groupid FROM user WHERE id = " . intval($userId));
		return $user ? $this->getGroupTeamByGroupId($user->groupid) : null;
	}

	/**
	 * Return team info based on group id
	 *
	 */
	public function getGroupTeamByGroupId($groupId) 
	{

		$row = \DB::selectOne("SELECT t.*, c.* FROM admin_teams t LEFT JOIN admin_team_client c ON (c.team_id=t.id) WHERE c.user_group_id = " . intval($groupId));
		return $row;
	}

	public function getOrderTurnTimeByDateDelivered($order, $deliveredDate)
	{
	    if ($deliveredDate) {

	        $diff = $this->getTotalNumberOfDays(strtotime($order->ordereddate), $deliveredDate);
	        return sprintf('0 Mo %s D %s H %s M', $diff['d'], $diff['h'], $diff['m']);
	    }
	    return '';
	}

	public function getTotalNumberOfDays($from, $to) 
	{
		$diff = abs( $from - $to  );
	    return ['d' => intval( $diff / 86400 ), 'h' => intval( ( $diff % 86400 ) / 3600), 'm' => intval( ( $diff / 60 ) % 60 ), 's' => intval( $diff % 60 )];
	}

}    