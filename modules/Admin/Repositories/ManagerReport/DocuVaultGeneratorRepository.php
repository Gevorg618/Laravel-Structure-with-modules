<?php

namespace Modules\Admin\Repositories\ManagerReport;

use Yajra\DataTables\Datatables;

class DocuVaultGeneratorRepository
{   
    
	protected $dateFrom;
	protected $dateTo;
	protected $dateType;
	protected $clients = [];
	protected $lenders = [];

	public function buildCSVDocument($clientsId, $dateRange, $dateType, $lendersId): array
	{
		$dateRange = explode("-", $dateRange);
        
        // Init
        $this->dateFrom = date('Y-m-d', strtotime($dateRange[0]));
        $this->dateTo = date('Y-m-d', strtotime($dateRange[1]));
        $this->clients = $clientsId;
        $this->lenders =  $lendersId;
        $this->dateType = $dateType;
        // dd($this->dateFrom);
        $all = $this->all();
        return $all;
	}

	public function all() {
		
		$all = array_merge($this->getAppraisals(), $this->getDocuVaults());

		return $all;
	}

	protected function _dateValue($date) {
	    switch($this->dateType) {
	      case 'notification_date':
	      case 'borrower_confirmation_date':
	      //case 'delivered_date':
	        return strtotime($date);
	    }

	    return $date;
	  }

	protected function _dateColumn() {
	    switch($this->dateType) {
	      case 'ordereddate':
	        return 'a.ordereddate';
	      case 'delivered_date':
	        return 'a.date_delivered';
	      case 'notification_date':
	      case 'borrower_confirmation_date':
	        return "IF(a.final_appraisal_borrower_sendtopostalmail='Y', l.delivered_date, d.created_date)";
	      default:
	        return 'n.created_date';
	    }
	}

	protected function getAppraisals() {
	    
	    
	    return \DB::select("SELECT a.id as 'Order ID', a.borrower as 'Borrower', a.ordereddate as 'Ordered Date', a.date_delivered as 'Report Delivered Date', g.descrip as 'Client Name', a.loanrefnum as 'Loan Reference Number', 
	            CONCAT(TRIM(CONCAT(a.propaddress1,' ',a.propaddress2)),',',a.propcity,', ',a.propstate,' ',a.propzip) as 'Property Address', 
	            FROM_UNIXTIME(n.created_date, '%m/%d/%Y') as 'Requested Date',
	            IF(a.final_appraisal_borrower_sendtopostalmail='Y','Yes','No') as 'Mailed', 
	            IF(l.delivered_date>0, FROM_UNIXTIME(l.delivered_date, '%m/%d/%Y'),'') as 'Mail Delivered Date', 
	            IF(a.final_appraisal_borrower_sendtoemail='Y','Yes','No') as 'Emailed',
	            IF(a.final_appraisal_borrower_sendtopostalmail='Y',IF(l.delivered_date>0, FROM_UNIXTIME(l.delivered_date, '%m/%d/%Y'),''), IF(d.created_date, FROM_UNIXTIME(d.created_date, '%m/%d/%Y'),'')) as 'Borrower Confirmation Date',
	            a.final_appraisal_borrower_sendtopostalmail_amount as 'DocuVault Mailing Fee', a.mail_paid_amount as 'DocuVault Mailing Fee Paid Amount'
	            FROM appr_order a
	            LEFT JOIN user_groups g ON (g.id=a.groupid)
	            LEFT JOIN document_vault_notification n ON (a.id=n.order_id)
	            LEFT JOIN document_vault_user du ON (a.id=du.orderid AND du.affiliation = 'borrower') 
	            LEFT JOIN document_vault_download d ON (d.vault_id=du.id AND d.final_report=1) 
	            LEFT JOIN appr_sent_mail l ON (a.id=l.orderid)
	            WHERE " . implode(' AND ', $this->_conditions()) ."GROUP BY a.id ORDER BY a.id ASC")
	    ;
	    
  	}

	protected function getDocuVaults() {

		return \DB::select("SELECT a.id as 'Order ID', a.borrower as 'Borrower', a.ordereddate as 'Ordered Date', a.ordereddate as 'Report Delivered Date', g.descrip as 'Client Name', a.loanrefnum as 'Loan Reference Number', 
		        CONCAT(TRIM(CONCAT(a.propaddress1,' ',a.propaddress2)),',',a.propcity,', ',a.propstate,' ',a.propzip) as 'Property Address', 
		        FROM_UNIXTIME(n.created_date, '%m/%d/%Y') as 'Requested Date',
		        IF(a.final_appraisal_borrower_sendtopostalmail='Y','Yes','No') as 'Mailed',
		        IF(l.delivered_date>0, FROM_UNIXTIME(l.delivered_date, '%m/%d/%Y'),'') as 'Mail Delivered Date', 
		        IF(a.final_appraisal_borrower_sendtoemail='Y','Yes','No') as 'Emailed',
		        IF(a.final_appraisal_borrower_sendtopostalmail='Y',IF(l.delivered_date>0, FROM_UNIXTIME(l.delivered_date, '%m/%d/%Y'),''), IF(d.created_date, FROM_UNIXTIME(d.created_date, '%m/%d/%Y'),'')) as 'Borrower Confirmation Date',
		        a.final_appraisal_borrower_sendtopostalmail_amount as 'DocuVault Mailing Fee', a.paid_amount as 'DocuVault Mailing Fee Paid Amount'
		        FROM appr_docuvault_order a
		        LEFT JOIN user_groups g ON (g.id=a.groupid)
		        LEFT JOIN document_vault_notification n ON (a.id=n.order_id)
		        LEFT JOIN document_vault_user du ON (a.id=du.orderid AND du.affiliation = 'borrower') 
		        LEFT JOIN document_vault_download d ON (d.vault_id=du.id AND d.final_report=1) 
		        LEFT JOIN appr_sent_mail l ON (a.id=l.orderid)
		        WHERE " . implode(' AND ', $this->_conditions(true)) .
		        "GROUP BY a.id ORDER BY a.id ASC");
		
	}

	 protected function _conditions($isDocuvault=false) {
	 	
        $conditions = [
          'status' => sprintf('a.status = %s', 6),
          'notification_date' => 'n.created_date IS NOT NULL',
          'final_appraisal' => "(a.final_appraisal_borrower_sendtopostalmail='Y' OR a.final_appraisal_borrower_sendtoemail='Y') ",
        ];

        // For Docuvault there is no delivered date
        // use ordered date instead
        $dateColumn = $this->_dateColumn();
        if($isDocuvault && $dateColumn == 'a.date_delivered') {
          	$dateColumn = 'a.ordereddate';
        }
        
        $conditions['datefrom'] = sprintf('%s >= %s', $dateColumn, \DB::connection()->getPdo()->quote($this->_dateValue($this->dateFrom)));

        $conditions['dateto'] = sprintf('%s <= %s', $dateColumn, \DB::connection()->getPdo()->quote($this->_dateValue($this->dateTo)));

        if($this->clients) {
          $conditions['clients'] = sprintf('a.groupid IN (%s)', implode(',', $this->clients));
        }

        if($this->lenders) {
          $conditions['lenders'] = sprintf('a.lender_id IN (%s)', implode(',', $this->lenders));
        }

        return $conditions;
    }
}    