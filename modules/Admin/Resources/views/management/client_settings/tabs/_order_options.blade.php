<div class="col-md-10">
    <h2>General</h2>
    <div class="form-group row">
        <label for="can_place_appraisal_orders" class="col-md-4">Clients can place appraisal orders?</label>
        <div class="col-md-4">
            <select name="can_place_appraisal_orders" id="can_place_appraisal_orders" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->can_place_appraisal_orders ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="show_valuclear" class="col-md-4">Show Alternative Valuation</label>
        <div class="col-md-4">
            <select name="show_valuclear" id="show_valuclear" class="form-control">
                <option value="N" {{$client->show_valuclear == 'N' ? 'selected' : ''}}>No</option>
                <option value="Y" {{$client->show_valuclear == 'Y' ? 'selected' : ''}}>Yes</option>
                <option value="ONLY" {{$client->show_valuclear == 'ONLY' ? 'selected' : ''}}>Only</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="show_group_as_lender" class="col-md-4">Show Group as a wholesale lender in the order page</label>
        <div class="col-md-4">
            <select name="show_group_as_lender" id="show_group_as_lender" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->show_group_as_lender ? 'selected' : ''}} >Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="invoice_show_processor" class="col-md-4">Show Processor Name On Invoice</label>
        <div class="col-md-4">
            <select name="invoice_show_processor" id="invoice_show_processor" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->invoice_show_processor ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="show_client_order_number" class="col-md-4">Show Order Client Number Field</label>
        <div class="col-md-4">
            <select name="show_client_order_number" id="show_client_order_number" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->show_client_order_number ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="require_client_order_number" class="col-md-4">Require Order Client Number Field</label>
        <div class="col-md-4">
            <select name="require_client_order_number" id="require_client_order_number" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->require_client_order_number ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="is_default_on_hold" class="col-md-4">Enter On Hold By Default?</label>
        <div class="col-md-4">
            <select name="is_default_on_hold" id="is_default_on_hold" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->is_default_on_hold ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p class="muted">Place all new orders coming in for this client as On Hold?</p>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="disable_appr_on_hold" class="col-md-4">Disable On Hold Message For Appraiser</label>
        <div class="col-md-4">
            <select name="disable_appr_on_hold" id="disable_appr_on_hold" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->disable_appr_on_hold ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="notify_order_placed_emails" class="col-md-4">Notify Order Placed Email Addresses</label>
        <div class="col-md-4">
            <textarea rows="2" cols="38" id="notify_order_placed_emails" name="notify_order_placed_emails"
                      class="form-control">{{$client->notify_order_placed_emails}}</textarea>
        </div>
    </div>

    <div class="form-group row">
        <label for="notify_order_placed_subject" class="col-md-4">Notify Order Placed Email Subject</label>
        <div class="col-md-4">
            <input type="text" name="notify_order_placed_subject" id="notify_order_placed_subject"
                   value="{{$client->notify_order_placed_subject}}"
                   class="form-control">
        </div>
    </div>

    <h2>Notify Order Placed Email Content</h2>

    <div class="row ">
        <div class="form-group col-md-4">
            <textarea name="notify_order_placed_content" id="notify_order_placed_content" cols="60"
                      rows="5">{{$client->notify_order_placed_content}}</textarea>
        </div>
    </div>


    <h2>Additional Emails</h2>
    <div class="form-group row">
        <label for="additional_email" class="col-md-4">Additional Emails</label>
        <div class="col-md-4">
            <textarea rows="2" cols="38" id="additional_email" name="additional_email"
                      class="form-control">{{$client->additional_email}}</textarea>
        </div>
    </div>

    <div class="form-group row">
        <label for="final_report_emails" class="col-md-4">Final Report Emails</label>
        <div class="col-md-4">
            <textarea rows="2" cols="38" id="final_report_emails" name="final_report_emails"
                      class="form-control">{{$client->final_report_emails}}</textarea>
        </div>
    </div>

    <div class="form-group row">
        <label for="add_email_status" class="col-md-4">Default Status Email</label>
        <div class="col-md-4">
            <select name="add_email_status" id="add_email_status" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->add_email_status ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="add_email_support" class="col-md-4">Default Supprt Email</label>
        <div class="col-md-4">
            <select name="add_email_support" id="add_email_support" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->add_email_support ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>

    <h2>Payment Optionss</h2>

    <div class="form-group row">
        <label for="allow_partial_payment" class="col-md-4">Allow Partial Payment</label>
        <div class="col-md-4">
            <select name="allow_partial_payment" id="allow_partial_payment" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->allow_partial_payment ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="allow_cod_payment" class="col-md-4">Allow COD Payment</label>
        <div class="col-md-4">
            <select name="allow_cod_payment" id="allow_cod_payment" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->allow_cod_payment  ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="enable_cfb_payment" class="col-md-4">Allow Collect From Borrower Payment</label>
        <div class="col-md-4">
            <select name="enable_cfb_payment" id="enable_cfb_payment" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->enable_cfb_payment ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="cod_payment_fee" class="col-md-4">COD Additional Fee</label>
        <div class="col-md-4">
            <input type="text" name="cod_payment_fee" id="cod_payment_fee" value="{{$client->cod_payment_fee }}"
                   class="form-control">
        </div>
    </div>

    <div class="form-group row">
        <label for="allow_check_payment" class="col-md-4">Allow Check Payment</label>
        <div class="col-md-4">
            <select name="allow_check_payment" id="allow_check_payment" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->allow_check_payment ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="creditcard_custom_price" class="col-md-4">Allow entering amount for credit card purchase</label>
        <div class="col-md-4">
            <select name="creditcard_custom_price" id="creditcard_custom_price" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->creditcard_custom_price ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="payment_time" class="col-md-4">When To Charge</label>
        <div class="col-md-4">
            <select id="payment_time" name="payment_time" class="form-control">
                <option value="0" {{($client->payment_time == '0') ? 'selected' : ''}} >Assignment</option>
                <option value="1" {{($client->payment_time == '1') ? 'selected' : ''}}>Inspection Complete</option>
                <option value="2" {{($client->payment_time == '2') ? 'selected' : ''}}>QC</option>
                <option value="3" {{($client->payment_time == '3') ? 'selected' : ''}}>Appraisal Complete</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="cc_borrower_payment" class="col-md-4">CC Borrower on Payment Email</label>
        <div class="col-md-4">
            <select name="cc_borrower_payment" id="cc_borrower_payment" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->cc_borrower_payment ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>

    <h2>RealView</h2>

    <div class="form-group row">
        <label for="realview_checklist" class="col-md-4">RealView Checklist</label>
        <div class="col-md-4">
            <select id="realview_checklist" name="realview_checklist" class="form-control">
                <option value="">-- None --</option>
                <option value="basic" {{($client->realview_checklist == 'basic') ? 'selected' : ''}}>RealView Basic
                </option>
                <option value="gold" {{($client->realview_checklist == 'gold') ? 'selected' : ''}}>RealView Gold
                </option>
                <option value="platinum" {{($client->realview_checklist == 'platinum') ? 'selected' : ''}}>RealView
                    Platinum
                </option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p>Select the RealView Checklist if you would like to automatically submit the report to
                    RealView upon appraisal upload.</p>
            </div>
        </div>
    </div>


    <h2>Order Page</h2>
    <div class="form-group row">
        <label for="appraisal_orders_qc_type" class="col-md-4">Appraisal QC Process Type</label>
        <div class="col-md-4">
            <select id="appraisal_orders_qc_type" name="appraisal_orders_qc_type" class="form-control">
                <option value="manual" {{($client->appraisal_orders_qc_type == 'manual') ? 'selected' : ''}}>Manual
                    Checklist
                </option>
                <option value="realview" {{($client->appraisal_orders_qc_type == 'realiview') ? 'selected' : ''}} >
                    RealView Checklist (Landscape)
                </option>
                <option
                    value="realviewhtml" {{($client->appraisal_orders_qc_type == 'realviewhtml') ? 'selected' : ''}}>
                    RealView Checklist HTML (RealView)
                </option>
                <option value="bypass" {{($client->appraisal_orders_qc_type == 'bypass') ? 'selected' : ''}}>Bypass
                    Checklist
                </option>
            </select>
        </div>
    </div>
    <p class="muted">Select the QC process that will apply to all orders placed under this client</p>

    <div class="form-group row">
        <label for="show_mortgage_associate" class="col-md-4">Show Mortgage Associate Field?</label>
        <div class="col-md-4">
            <select name="show_mortgage_associate" id="show_mortgage_associate" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->show_mortgage_associate ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="enable_appr_schedule_dates" class="col-md-4">Allow Possible Scheduling Dates?</label>
        <div class="col-md-4">
            <select name="enable_appr_schedule_dates" id="enable_appr_schedule_dates" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->enable_appr_schedule_dates ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="show_reqfha" class="col-md-4">Show option for requires FHA appraiser for conventional</label>
        <div class="col-md-4">
            <select name="show_reqfha" id="show_reqfha" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->show_reqfha  ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p class="muted">If Yes, this will show a checkbox on 2nd page of the order process "Require FHA
                    Appraiser", which the user can select.</p>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="require_purchasecontract" class="col-md-4">Require Sales Contract</label>
        <div class="col-md-4">
            <select name="require_purchasecontract" id="require_purchasecontract" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->require_purchasecontract  ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p class="muted">If Yes, This will require the user to upload a sales contract when the loan is
                    'Purchase'</p>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="hide_units" class="col-md-4">Hide Units (default to 1)</label>
        <div class="col-md-4">
            <select name="hide_units" id="hide_units" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->hide_units  ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p class="muted">If Yes, this will hide the units field and default it to 1 unit for all orders</p>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="show_uwallusers" class="col-md-4">UW options to all users</label>
        <div class="col-md-4">
            <select name="show_uwallusers" id="show_uwallusers" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->show_uwallusers  ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p class="muted">If Yes, this will show the U/W options to all users in this group (NOT
                    RECOMMENDED).</p>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="show_uwmgrusers" class="col-md-4">UW options to User Managers</label>
        <div class="col-md-4">
            <select name="show_uwmgrusers" id="show_uwmgrusers" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->show_uwmgrusers  ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p class="muted">If Yes, this show the U/W options to user managers in this group</p>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="auto_req_investdocs" class="col-md-4">Auto Require Investment Docs for Orders marked
            Investment</label>
        <div class="col-md-4">
            <select name="auto_req_investdocs" id="auto_req_investdocs" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->auto_req_investdocs  ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p class="muted">If Yes, when a property is marked as "Investment" the Investment Documents will be
                    required to place the order</p>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="opt_contactentry" class="col-md-4">Contact for Entry Optional</label>
        <div class="col-md-4">
            <select name="opt_contactentry" id="opt_contactentry" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->opt_contactentry   ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p class="muted">If Yes, this will not require the contact for entry on order placement, contact for
                    <br>
                    entry is the only information displayed to the appraiser</p>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="req_loannum" class="col-md-4">Require Loan Ref #</label>
        <div class="col-md-4">
            <select name="req_loannum" id="req_loannum" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->req_loannum  ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p class="muted">If Yes, this will require the Loan Ref # on order placement.</p>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="show_paylater" class="col-md-4">Pay Later/Borrower Paying</label>
        <div class="col-md-4">
            <select name="show_paylater" id="show_paylater" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->show_paylater  ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p class="muted">If No, this will hide the option for "Pay Later/Borrower Pay"</p>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="show_paynow" class="col-md-4">Payment Now</label>
        <div class="col-md-4">
            <select name="show_paynow" id="show_paynow" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->show_paynow  ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p class="muted">If No, this will hide the option for "Credit Card Pay Now"</p>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="show_price" class="col-md-4">Pricing</label>
        <div class="col-md-4">
            <select name="show_price" id="show_price" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->show_price   ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p class="muted">If No, this will hide the pricing table during order placement</p>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="def_occ" class="col-md-4">Default Occupancy Status</label>
        <div class="col-md-4">
            <select name="def_occ" id="def_occ" class="form-control">
                <option value="0"> </option>>
                @foreach($occupancyStatusesList as  $key  => $value)
                    <option {{($client->def_occ == $key) ? 'selected' : '' }}  value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p class="muted">Select the default Occupancy Status, blank will require the user to select one</p>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="def_loantype" class="col-md-4">Default Loan Type</label>
        <div class="col-md-4">
            <select id="def_loantype" name="def_loantype" class="form-control">
                <option value="0"> </option>
                @foreach($getLoanTypesList as $key  => $value)
                    <option {{($client->def_loantype == $key) ? 'selected' : '' }}  value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p class="muted">Select the default Loan Type, blank will require the user to select one</p>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="req_loannum" class="col-md-4">Default Units</label>
        <div class="col-md-4">
            <input type="text" name="def_units" id="def_units" value="{{$client->def_units }}"
                   class="form-control input-medium bfh-phone"
                   data-format="ddddd">
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p class="muted">Select the default Loan Type, blank will require the user to select one</p>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="is_default_on_hold" class="col-md-4">Hide Legal Description</label>
        <div class="col-md-4">
            <select name="hidelegaldescrip" id="hidelegaldescrip" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->hidelegaldescrip  ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p class="muted">If Yes, the legal description field will be hidden during order placement</p>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="standard_legal" class="col-md-12">Standard Legal Desc</label>
        <div class="col-md-8">
            <textarea rows="5" cols="80" id="standard_legal" name="standard_legal"
                      class="form-control">{{$client->standard_legal}}</textarea>
        </div>
    </div>

    <?php   $apprType = array_flip(explode(',', $client->show_apprtype)); ?>
    <h2>Limit Appraisal Types</h2>
    <div class="form-group row">
        @foreach($apprTypeListY as $key  => $value)
            <div class="col-md-6">
                <input type="checkbox" {{isset($apprType[$key]) ? 'checked' : '' }}  name="show_apprtype[{{$key}}]"
                       id="show_apprtype_{{$key}}" value="{{$key}}">
                <label for="show_apprtype_{{$key}}">{{$value}}</label>
            </div>
        @endforeach
    </div>

    <?php   $loantype = array_flip(explode(',', $client->show_loantype)); ?>
    <h2>Limit Loan Types</h2>
    <div class="form-group row">
        @foreach($getLoanTypesList as $key  => $value)
            <div class="col-md-6">
                <input type="checkbox" {{isset($loantype[$key]) ? 'checked' : '' }}   name="show_loantype[{{$key}}]"
                       id="show_loantype_{{$key}}" value="{{$key}}">
                <label for="show_loantype_{{$key}}">{{$value}}</label>
            </div>
        @endforeach
    </div>

    <?php   $loanpurpose = array_flip(explode(',', $client->show_loanpurpose)); ?>
    <h2>Limit Loan Purpose</h2>
    <div class="form-group row">
        @foreach($getReasonsList as $item )
            <div class="col-md-6">
                <input type="checkbox"
                       {{isset($loanpurpose[$item->id]) ? 'checked' : '' }}  name="show_loanpurpose[{{$item->id}}]"
                       id="show_loanpurpose_{{$item->id}}" value="{{$item->id}}">
                <label for="show_loanpurpose_{{$item->id}}">{{$item->descrip}}</label>
            </div>
        @endforeach
    </div>

    <?php   $propertytype = array_flip(explode(',', $client->show_propertytype)); ?>
    <h2>Limit Property Types</h2>
    <div class="form-group row">
        @foreach($propertyTypeList as $item )
            <div class="col-md-6">
                <input type="checkbox"
                       {{isset($propertytype[$item->id]) ? 'checked' : '' }}  name="show_propertytype[{{$item->id}}]"
                       id="show_propertytype_{{$item->id}}" value="{{$item->id}}">
                <label for="show_propertytype_{{$item->id}}">{{$item->descrip}}</label>
            </div>
        @endforeach
    </div>

    <h2>Order Handling</h2>
    <div class="form-group row">
        <label for="tila_auth" class="col-md-4">TILA Authorization Required</label>
        <div class="col-md-4">
            <select name="tila_auth" id="tila_auth" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->tila_auth ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="attach_hvcc_cert" class="col-md-4">Attach ICC Cert to final reports</label>
        <div class="col-md-4">
            <select name="attach_hvcc_cert" id="attach_hvcc_cert" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->attach_hvcc_cert  ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="attach_hvcc_cert_forfha" class="col-md-4">Attach ICC Cert to FHA final reports</label>
        <div class="col-md-4">
            <select name="attach_hvcc_cert_forfha" id="attach_hvcc_cert_forfha" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->attach_hvcc_cert_forfha   ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p class="muted">If Yes, attaches the ICC cert to the final report even if its a FHA order.</p>
            </div>
        </div>
    </div>

    <h2>Email Controls</h2>
    <div class="form-group row">
        <label for="master_email_control" class="col-md-4">Disable All Email Communication</label>
        <div class="col-md-4">
            <select name="master_email_control" id="master_email_control" class="form-control">
                <option value="OFF">No</option>
                <option value="ON" {{$client->master_email_control == 'ON' ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="supress_inspcomplete" class="col-md-4">Disable Inspection Complete Status Emails</label>
        <div class="col-md-4">
            <select name="supress_inspcomplete" id="supress_inspcomplete" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->supress_inspcomplete  ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="lender_final_email" class="col-md-4">Send Wholesale Lender Final Report Email</label>
        <div class="col-md-4">
            <select name="lender_final_email" id="lender_final_email" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->lender_final_email ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p class="muted">Disables/Enables the email type for all users in this group, User Manager settings<br>
                    will override these settings for that user. If Final report notification is set to "Yes" that<br>
                    will disable the User Manager override of the final report, set both to "No" to disable sending<br>
                    to the user but still send to the User Manager.</p>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="send_xml_report" class="col-md-4">Send XML Report</label>
        <div class="col-md-4">
            <select name="send_xml_report" id="send_xml_report" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->send_xml_report  ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="emailcontrol_status" class="col-md-4">Status Emails</label>
        <div class="col-md-4">
            <select name="emailcontrol_status" id="emailcontrol_status" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->emailcontrol_status  ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="emailcontrol_support" class="col-md-4">Support Emails</label>
        <div class="col-md-4">
            <select name="emailcontrol_support" id="emailcontrol_support" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->emailcontrol_support  ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="emailcontrol_final" class="col-md-4">Final Report Emails</label>
        <div class="col-md-4">
            <select name="emailcontrol_final" id="emailcontrol_final" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->emailcontrol_final  ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
</div>
@push('scripts')
    <script src="{{ masset('js/management/client_settings/edit.js') }}"></script>
@endpush


