|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|POST|/order/add|add_order|Create New Order|-|
|POST|/order/update|update_order|Update order information|-|
	

### Required Fields

|Name|Description|Notes|
|--- |--- |--- |
|orderedby|Client ID or email address|Must exist in the system (Or use user_email/user_password below instead)|
|user_email|Client email address|Must exist in the system. User either orderedby ID or combination of user_email/user_password|
|user_password|plain text password|Must be plain text and transmitted over HTTPS if user_email/user_password combination is used.|
|propaddress1|Property Address|plain Text|
|propcity|Property City|plain text|
|propstate|Property State|plain text (CA, TX etc..)|
|propzip|Property Zip Code|plain text (xxxxx)|
|prop_type|Property Type|plain text Query /order/property-types and pass in a number representing the property type|
|occstatus|Property Occupancy Status|plain text Query /order/occ-statuses and pass in a number representing the occupancy status|
|loantype|Property Loan Type|plain text Query /order/loan-types and pass in a number representing the loan type|
|fha_case|FHA Case Number|plain text. Only required when the req_fha is Y or loantype is FHA (2)|
|loanpurpose|Property Loan Purpose|plain text Query /order/loan-purposes and pass in a number representing the loan purpose|
|no_units|Property Number of units|plain text (xxxxx)|
|appr_type|Property Appraisal Type|plain text Query /order/appraisal-types and pass in a number representing the appraisal type|
|lender|Lender Name|plain text|
|lenderaddress|Lender Address|plain text|
|lendercity|Lender City|plain text|
|lenderstate|Lender State|plain text|
|lenderzip|Lender Zip Code|plain text (xxxxx)|
|borrower|Borrower Name|plain text|
|borrower_phone|Borrower Phone|plain text|
|contactname|Contact For Entry name|plain text|
|contactphone|Contact For Entry Phone|plain text|
|contactemail|Contact For Entry Email|plain text|


### Optional Fields

|Name|Description|Notes|
|--- |--- |--- |
|acceptedby|Accepted By (Appraiser)|plain text|
|appr_assigned|Appraiser Assigned to this order|plain text|
|accepteddate|Accepted Date (yyyy-mm-dd hh:ii:ss)|plain text|
|status|Order Status|plain text|
|schd_date|Scheduled Date (yyyy-mm-dd)|plain text|
|sched_dts|scheduled Date & Time (yyyy-mm-dd hh:ii:ss)|plain text|
|completed|Completed Date (yyyy-mm-dd hh:ii:ss)|plain text|
|submitted|Submitted Date (yyyy-mm-dd hh:ii:ss)|plain text|
|propaddress1|Property Address|plain text|
|propaddress2|Property Address (Suite, APT)|plain text|
|propcity|Property City|plain text|
|propstate|Property State (CA, TX etc..)|plain text|
|propzip|Property Zip|plain text|
|req_fha|Order Requires FHA (Y/N)|plain text|
|fha_case|FHA Case Number|plain text|
|lender_id|Lender ID (must be an ID that exists in Landscape @see "order/client-options" on how to get a list of lenders available for the client placing the order)|plain text|
|invoicename|Invoice Name|plain text|
|invoiceaddress|Invoice Address|plain text|
|invoicecity|Invoice City|plain text|
|invoicestate|Invoice State|plain text|
|invoicezip|Invoice Zip|plain text|
|borrower_altphone|Borrower Alternative Phone|plain text|
|loanrefnum|Loan Reference Number|plain text|
|sales_contract|Sales Contract (Y/N)|plain text|
|fax_contract|Fax Contract (Y/N)|plain text|
|sales_price|Sales Price|plain text|
|legal_descrip|Legal Description|plain text|
|contactphone2|Contact Alternative Phone|plain text|
|contactnotes|Contact Notes|plain text|
|comments|Comments|plain text|
|billmelater|Bill Me Later Payment (Y/N)|plain text|
|split_amount|Split Amount|plain text|
|trip_fee|Trip Fee|plain text|
|complex_prop|Complex Property (Y/N)|plain text|
|appr_paid|Amount Paid (xx.xx)|plain text|
|amountdue|Amount Due (xx.xx)|plain text|
|invoicedue|Invoice Due (xx.xx)|plain text|
|paid_amount|Paid Amount (xx.xx)|plain text|
|is_cod|Is A COD Order (Y/N)|plain text|
|payment_method|Payment Method|plain text|
|additional_email|Additional Emails (one per line)|plain text|
|final_report_emails|Final Report Emails (one per line)|plain text|
|add_email_status|Status Emails (Y/N)|plain text|
|add_email_support|Support Emails (Y/N)|plain text|
|cc_name|Credit Card Holders Name (Required)|plain text|
|cc_number|Credit Card Number (Required)|plain text|
|cc_exp|Credit Card Expiration (mmyy) (Required)|plain text|
|cc_cvv|Credit Card Verification Code (Required)|plain text|
|cc_billaddress|Credit Card Billing Address (Required)|plain text|
|cc_billcity|Credit Card Billing City (Required)|plain text|
|cc_billstate|Credit Card Billing State (Required)|plain text|
|cc_billzip|Credit Card Billing Zip (Required)|plain text|


## Add Order

#### Returns

<div>On success will return the newly created order information (as if you were calling /order/X where X being the new order id)</div>

#### Request:

```json
{baseurl}/order/add/key/XXX/...
```

#### Result:

```json
{
   "id":"14154083",
   "orderedby":"120400",
   "acceptedby":"115228",
   "appr_assigned":"115228",
   "accepteddate":"2012-07-30 15:00:00",
   "status":"6",
   "schd_date":"2012-07-31 00:00:00",
   "sched_dts":"2012-07-31 00:00:00",
   "completed":"2012-08-03 11:36:00",
   "submitted":"2012-08-03 11:36:00",
   "propaddress1":"1785 MEADOW CREEK LN #2B",
   "propaddress2":"",
   "propcity":"OGDEN",
   "propstate":"UT",
   "propzip":"84403",
   "prop_type":"0",
   "occstatus":"1",
   "loantype":"1",
   "req_fha":"N",
   "loanpurpose":"0",
   "lender":"Security One Lending - Wholesale",
   "lenderaddress":"3131 Camino Del Rio N #1400",
   "lendercity":"San Diego",
   "lenderstate":"CA",
   "lenderzip":"92108",
   "invoicename":"",
   "invoiceaddress":"",
   "invoicecity":"",
   "invoicestate":"",
   "invoicezip":"",
   "borrower":"JUDY & MERLIN C LOPER",
   "borrower_email":"",
   "borrower_phone":"",
   "borrower_altphone":"",
   "loanrefnum":"",
   "no_units":"",
   "sales_contract":"N",
   "fax_contract":"N",
   "sales_price":"0",
   "legal_descrip":"",
   "appr_type":"1",
   "contactname":"JUDY & MERLIN C LOPER",
   "contactphone":"801-479-4054",
   "contactphone2":"",
   "contactemail":"",
   "contactnotes":"",
   "comments":"",
   "billmelater":"N",
   "split_amount":"350.00",
   "trip_fee":"N",
   "complex_prop":"N",
   "appr_paid":"0000-00-00 00:00:00",
   "amountdue":"0.00",
   "invoicedue":"450.00",
   "paid_amount":"450.00",
   "is_cod":"N"
}
```

<div>On failure will return a json object stating the error code and message</div>

```json
{"error":"Order information is missing. Please submit the X Field.","code":402}
{"error":"User with the id '%s' was not found.","code":403}
```

## Update Order

#### Returns

<div>On success will return the updated order information (as if you were calling /order/X where X being the new order id)</div>

#### Request:

```json
{baseurl}/order/update/key/XXX/...
```

#### Result:

```json
{
   "id":"14154083",
   "orderedby":"120400",
   "acceptedby":"115228",
   "appr_assigned":"115228",
   "accepteddate":"2012-07-30 15:00:00",
   "status":"6",
   "schd_date":"2012-07-31 00:00:00",
   "sched_dts":"2012-07-31 00:00:00",
   "completed":"2012-08-03 11:36:00",
   "submitted":"2012-08-03 11:36:00",
   "propaddress1":"1785 MEADOW CREEK LN #2B",
   "propaddress2":"",
   "propcity":"OGDEN",
   "propstate":"UT",
   "propzip":"84403",
   "prop_type":"0",
   "occstatus":"1",
   "loantype":"1",
   "req_fha":"N",
   "loanpurpose":"0",
   "lender":"Security One Lending - Wholesale",
   "lenderaddress":"3131 Camino Del Rio N #1400",
   "lendercity":"San Diego",
   "lenderstate":"CA",
   "lenderzip":"92108",
   "invoicename":"",
   "invoiceaddress":"",
   "invoicecity":"",
   "invoicestate":"",
   "invoicezip":"",
   "borrower":"JUDY & MERLIN C LOPER",
   "borrower_email":"",
   "borrower_phone":"",
   "borrower_altphone":"",
   "loanrefnum":"",
   "no_units":"",
   "sales_contract":"N",
   "fax_contract":"N",
   "sales_price":"0",
   "legal_descrip":"",
   "appr_type":"1",
   "contactname":"JUDY & MERLIN C LOPER",
   "contactphone":"801-479-4054",
   "contactphone2":"",
   "contactemail":"",
   "contactnotes":"",
   "comments":"",
   "billmelater":"N",
   "split_amount":"350.00",
   "trip_fee":"N",
   "complex_prop":"N",
   "appr_paid":"0000-00-00 00:00:00",
   "amountdue":"0.00",
   "invoicedue":"450.00",
   "paid_amount":"450.00",
   "is_cod":"N"
}
```

<div>When updating must pass the order ID to update</div>

