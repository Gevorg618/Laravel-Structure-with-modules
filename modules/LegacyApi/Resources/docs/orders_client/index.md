### Available Fields

|Field Key|Field Description|
|--- |--- |
|id|Order ID|
|ordereddate|Ordered Date|
|orderedby|Ordered By (Client)|
|acceptedby|Accepted By (Appraiser)|
|appr_assigned|Appraiser Assigned to this order|
|accepteddate|Accepted Date (yyyy-mm-dd hh:ii:ss)|
|status|Order Status|
|groupid|Group ID|
|schd_date|Scheduled Date (yyyy-mm-dd) (DEPRECATED)|
|sched_dts|Scheduled Date & Time (yyyy-mm-dd hh:ii:ss)|
|completed|Completed Date (yyyy-mm-dd hh:ii:ss)|
|submitted|Submitted Date (yyyy-mm-dd hh:ii:ss)|
|propaddress1|Property Address|
|propaddress2|Property Address (Suite, APT)|
|propcity|Property City|
|propstate|Property State (CA, TX etc..)|
|propzip|Property Zip|
|prop_type|Property Type|
|occstatus|OCC Status|
|loantype|Loan Type|
|req_fha|Order Requires FHA (Y/N)|
|fha_case|FHA Case Number|
|loanpurpose|Loan Purpose|
|lender_id|Lender ID (must be an ID that exists in Landscape @see [Order Client Options](/orders_client/get_client_order_options/) on how to get a list of lenders available for the client placing the order)|
|lender|Lender|
|lenderaddress|Lender Address|
|lendercity|Lender City|
|lenderstate|Lender State|
|lenderzip|Lender Zip|
|invoicename|Invoice Name|
|invoiceaddress|Invoice Address|
|invoicecity|Invoice City|
|invoicestate|Invoice State|
|invoicezip|Invoice Zip|
|borrower|Borrower Name|
|borrower_email|Borrower Email|
|borrower_phone|Borrower Phone|
|borrower_altphone|Borrower Alternative Phone|
|co_borrower|Co-Borrower Name|
|co_borrower_email|Co-Borrower Email|
|co_borrower_phone|Co-Borrower Phone|
|co_borrower_altphone|Co-Borrower Alternative Phone|
|loanrefnum|Loan Reference Number|
|no_units|Number of units|
|sales_contract|Sales Contract (Y/N)|
|fax_contract|Fax Contract (Y/N)|
|sales_price|Sales Price|
|legal_descrip|Legal Description|
|appr_type|Appraisal Type|
|contactname|Contact Name|
|contactphone|Contact Phone|
|contactphone2|Contact Alternative Phone|
|contactemail|Contact Email|
|contactnotes|Contact Notes|
|comments|Comments|
|billmelater|Bill Me Later Payment (Y/N)|
|split_amount|Split Amount|
|trip_fee|Trip Fee|
|complex_prop|Complex Property (Y/N)|
|appr_paid|Amount Paid (xx.xx)|
|amountdue|Amount Due (xx.xx) (DEPRECATED)|
|invoicedue|Invoice Due (xx.xx)|
|paid_amount|Paid Amount (xx.xx)|
|is_cod|Is A COD Order (Y/N)|
|payment_method|Payment Method|
|additional_email|Additional Emails (one per line)|
|final_report_emails|Final Report Emails (one per line)|
|add_email_status|Status Emails (Y/N)|
|add_email_support|Support Emails (Y/N)|
|cc_name|Credit Card Holders Name (Required)|
|cc_number|Credit Card Number (Required)|
|cc_exp|Credit Card Expiration (mmyy) (Required)|
|cc_cvv|Credit Card Verification Code (Required)|
|cc_billaddress|Credit Card Billing Address (Required)|
|cc_billcity|Credit Card Billing City (Required)|
|cc_billstate|Credit Card Billing State (Required)|
|cc_billzip|Credit Card Billing Zip (Required)|
|addendas|Array of Addendas to attach to the order (addenda ids see Order Addendas)|
|date_delivered|Delivered Date & Time (yyyy-mm-dd hh:ii:ss)|
|fha_case_effective_date|FHA Case Effective Date (yyyy-mm-dd) (Required Starting Jan 1 2016)|
|is_new_construction|New Construction (0/1) (Required Starting Jan 1 2016)|
