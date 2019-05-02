<?php

use Illuminate\Database\Seeder;
use App\Models\Integrations\MercuryNetwork\MercuryStatus;

class MercuryStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * The key in array is unique and it isn't changeable
     * If You want to add row add it in the end, because they display in alphabetical order  
     * @return void
     */
    public function run()
    {
        $statuses = [
	        100000 => 'Open File',
	        101000 => 'Awaiting acceptance',
	        101010 => 'Requires assignment',
	        101020 => 'Vendor Accepted Assignment',
	        200999 => 'Create Order From Fax',
	        201000 => 'In Progress',
	        201010 => 'Conditionally declined (See Notes)',
	        201020 => 'Declined (See Notes)',
	        201030 => 'Conditions accepted by client',
	        201040 => 'Inspection Scheduled',
	        201050 => 'Inspection Complete',
	        201060 => 'Delayed',
	        201070 => 'On hold',
	        201080 => 'Reassigned',
	        201090 => 'Resumed',
	        202000 => 'Document Uploaded',
	        202001 => 'Document Deleted',
	        202002 => 'Completed Appraisal Deleted',
	        203000 => 'Order Changed',
	        203010 => 'Modification Requested',
	        203020 => 'Modification Accepted',
	        203030 => 'Modification Declined',
	        204010 => 'Payment Pending',
	        204020 => 'Payment Processed',
	        204030 => 'Payment Failed',
	        204040 => 'Payment Information Updated',
	        300020 => 'Cancelled',
	        301000 => 'Completed',
	        301002 => 'Pending Quality Review',
	        301005 => 'Pending Appraiser Review',
	        301006 => 'Review Accepted',
	        301010 => 'Revision Needed',
	        301011 => 'Revision Needed (Post Completion)',
	        301012 => 'Revision Request Cancelled',
	        301015 => 'Revision Uploaded',
	        301020 => 'Override Requested',
	        301021 => 'Override Rejected',
	        301022 => 'Override Accepted',
	        301030 => 'Appraisal Delivery Receipt',
	        301031 => 'Appraisal Delivered to MercuryDirect Partner',
	        301032 => 'Appraisal Delivery Failed',
	        400000 => '1st Reminder Sent',
	        400001 => '2nd Reminder Sent',
	        400010 => 'Order Reassigned To Subaccount',
	        400020 => 'Order Requires Update Reminder Sent',
	        400030 => 'Past Due Reminder Sent',
	        500001 => 'Report Viewed',
	        500002 => 'Document Viewed',
	        500003 => 'Invoice Sent',
	        500004 => 'Copy of completed appraisal e-mailed to borrower',
	        500005 => 'Appraisal viewed by borrower',
	        600000 => 'Requires Disclosure Date',
	        600001 => 'Update Disclosure Date',
	        600002 => 'Awaiting Disclosure Expiration Date',
	        700000 => 'Appraisal Submitted to {0} via UCDP',
	        700005 => 'Appraisal Submission Accepted by {0} via UCDP',
	        700006 => 'Appraisal Submission to {0} Not Successful',
	        700010 => 'Appraisal Submission to {0} via UCDP Failed',
	        700015 => 'Document File ID Added to Order',
	        700020 => 'UCDP Status Removed',
	        900000 => 'Message',
	        900010 => 'Note Added',
	        900020 => 'Appraisal Fee Changed',
	        900030 => 'Comment - Action Required',
	    ];
	    foreach ($statuses as $key => $value) {
	    	MercuryStatus::updateOrCreate(
	    		[ 'external_id' => $key ],
	    		[ 'title' => $value ]
	    	);
	    }
    }
}
