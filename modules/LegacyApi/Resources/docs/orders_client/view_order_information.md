<p>By default you'll only be able to view the orders you've created using the API key used when creating the order.</p>


|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|GET|/order/:id|view_order|Fetch order information by order id|/order/view/id/:id|



#### Request:

```json
{baseurl}/order/14154083/key/XXX
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