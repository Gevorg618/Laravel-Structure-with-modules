<p>Example POST Request with the Appraisal Order information sent to the subscriber URL</p>

```json
{
   "id":"200042300",
   "ordereddate":"2015-10-26 13:04:32",
   "orderedby":"124566",
   "acceptedby":"0",
   "appr_assigned":"0",
   "accepteddate":"2015-10-26 13:04:32",
   "status":"2",
   "groupid":"3293",
   "schd_date":null,
   "sched_dts":null,
   "completed":null,
   "submitted":null,
   "propaddress1":"5440 Tujunga Ave",
   "propaddress2":"",
   "propcity":"North Hollywood",
   "propstate":"CA",
   "propzip":"91601",
   "prop_type":"1",
   "occstatus":"1",
   "loantype":"1",
   "req_fha":"N",
   "fha_case":"",
   "loanpurpose":"3",
   "lender_id":"-1",
   "lender":"Landmark Network, Inc.",
   "lenderaddress":"5161 Lankershim Blvd, Suite 240",
   "lendercity":"North Hollywood",
   "lenderstate":"CA",
   "lenderzip":"91601",
   "invoicename":"",
   "invoiceaddress":"",
   "invoicecity":"",
   "invoicestate":"",
   "invoicezip":"",
   "borrower":"test",
   "borrower_email":"",
   "borrower_phone":"(121) 312-3123",
   "borrower_altphone":"",
   "loanrefnum":"",
   "no_units":"1",
   "sales_contract":"N",
   "fax_contract":"N",
   "sales_price":"0",
   "legal_descrip":"",
   "appr_type":"1",
   "contactname":"test",
   "contactphone":"(121) 312-3123",
   "contactphone2":"",
   "contactemail":"",
   "contactnotes":"",
   "comments":"",
   "billmelater":"Y",
   "split_amount":"0.00",
   "trip_fee":"N",
   "complex_prop":"N",
   "appr_paid":null,
   "amountdue":"425.00",
   "invoicedue":"425.00",
   "paid_amount":"0.00",
   "is_cod":"N",
   "payment_method":"invoice",
   "additional_email":"",
   "final_report_emails":"",
   "add_email_status":"N",
   "add_email_support":"N",
   "date_delivered":null,
   "fha_case_effective_date":null,
   "is_new_construction":"0",
   "addendas":[

   ]
}
```							 



<p>PHP Example to capture the incoming POST request on the Subscriber side</p>

```php
// get the raw POST data
$rawData = file_get_contents("php://input");

// this returns null if not valid json
$orderData = json_decode($rawData);
// $orderData will be the example json above
```						 
