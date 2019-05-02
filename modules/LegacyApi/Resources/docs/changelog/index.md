*   **October 22, 2015**
    *   Orders: AMC Vendors can now access orders assigend to them by passing a user_email/user_password of an existing amc account on the instance.
    *   Orders: AMC Vendors can now upload the final report to orders assigned to them.
    *   Orders: AMC Vendors, now can be notified of new orders assigned to them via the Subscribers when creating a new Subscriber pass in the 'appraisal_assign' key.
*   **October 14, 2015**
    *   Orders: Client Options will now return 'groups' with a list of all groups the user can submit to and is a manager of.
*   **October 5, 2015**
    *   Orders: Updated pricing structure to support TRID pricing.
*   **September 25, 2015**
    *   **BC:** Orders: Added two new fields per HUD requirements - [fha_case_effective_date](/orders_client/#fha_case_effective_date) and [is_new_construction](/orders_client/#is_new_construction). This change will require these two fields starting Jan 1st 2016.
*   **August 18, 2015**
    *   Orders: List API Call can now accept [fields](/orders_client/list_client_orders.html#additional-fields) parameter to include additional order information in the response.
*   **August 5, 2015**
    *   Orders: Tickets Submitted will be created with the PST timezone
    *   Orders: Order Placed - API log entry will be now public
    *   Orders: Order Related Information Calls (Appraisal Types, Loan Types, Loan Purpose, Property Types) will now return data based on the user information provided (if any).
*   **June 11, 2015**
    *   Orders: Documented the Addenads [Order Field](/orders_client/)
*   **May 20, 2015**
    *   Orders: Documented the Co-Borrower [Order Fields](/orders_client/)
    *   Orders: Added ability to list [Order Document Types](/orders_client/view_order_related_information.html#appraisal-document-types)
    *   Orders: Added ability to list [Order Addendas](/orders_client/view_order_related_information.html#appraisal-addendas)
    *   Orders: [Client Options](/orders_client/get_client_order_options) lender information will now return the lender address, city, state and zip.
*   **January 20, 2015**
    *   Orders: Added support for vendors to [List Assigned Orders](/orders_vendor/list_orders)
*   **January 15, 2015**
    *   Orders: Added option to submit vendor inquries by vendors through the API.
    *   Orders: Added support for vendors to [Upload Final Report](/orders_vendor/upload_final_report) as XML/PDF to an appraisal order.
*   **November 3, 2014**
    *   Added [Subscribers](/Subscribers/) Support (Post-Backs)
*   **August 21, 2014**
    *   Updated [Client Options](/orders_client/get_client_order_options) to include available lenders to submit to
    *   Added Order Fields: Additional Emails, Final Report Emails, Status Emails, Support Emails
    *   Added Order Fields: Lender ID
*   **August 6, 2014**
    *   Added the ability to view [Client Pricing](/orders_client/view_client_pricing_information) for appraisal types in states
    *   Updated API to add Addendas to required Appraisal Types
*   **June 3, 2014**
    *   User Password should be transfered as plain text over HTTPS not MD5 hash.
*   **April 28, 2014**
    *   Added new API method 'order/client-options' to show client specific options such as 'paymentOptions'
    *   Placing a new appraisal order will validate the payment option selected. Set payment method by specifying the 'payment\_method' field or individually with 'is\_cod' / 'billmelater'
*   **March 24, 2014**
    *   Added requirement to submit the from email address when adding new order inquries
*   **January 23, 2013**
    *   Added requirement to submit the 'fha_case' field when the order is FHA
*   **November 22, 2013**
    *   Added ability to specify user who created the order by passing 'user\_email' and 'user\_password'
*   **November 11, 2013**
    *   Added Appraisal XML API call to return the appraisal XML document
    *   Added API call to retrive the order invoice document
*   **October 28, 2013**
    *   Updated order log api call to return the HTML contents
    *   Updated credit card processing to process all payments through First Data Global Gateway
*   **October 25, 2013**
    *   Updated appraisal api call to return base64 encoded string of the document
    *   Updated icc certification api call to return base64 encoded string of the document