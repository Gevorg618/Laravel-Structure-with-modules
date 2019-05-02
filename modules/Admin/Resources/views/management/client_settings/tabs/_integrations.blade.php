<div class="col-md-10">
    <h2>API Associated Accounts</h2>
    <div class="form-group">
        <div class="col-md-4">
            {!! Form::select('apis[]',$apiUserList, $client->apiUsers,
            ['class'=>'form-control multiselect bootstrap-multiselect multiselect-apprTypeList', 'id'=>'apis', 'multiple']) !!}
        </div>
    </div>
    <h2>Mercury Settings</h2>
    <div class="form-group row">
        <label for="mercury_client_id" class="col-md-4">Mercury Client ID</label>
        <div class="col-md-4">
            <input type="text" name="mercury_client_id" id="mercury_client_id"
                   value="{{$client->mercury_client_id}}"
                   class="form-control">
        </div>
    </div>
    <div class="form-group row">
        <label for="mercury_catch_all_user_id" class="col-md-4">Company Name</label>
        <div class="col-md-4">
            <input type="text" name="mercury_catch_all_user_id" id="mercury_catch_all_user_id"
                   value="{{$client->mercury_catch_all_user_id}}"
                   class="form-control">
        </div>
    </div>
    <div class="form-group row">
        <label for="mercury_send_borrower_payment_collection" class="col-md-4">Email Borrower For Payment
            Collection</label>
        <div class="col-md-4">
            <select name="mercury_send_borrower_payment_collection" id="mercury_send_borrower_payment_collection"
                    class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->mercury_send_borrower_payment_collection   ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p>Send an email to the borrower if an order comes in with the Pay Later/Deffered CC payment type.</p>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="mercury_send_borrower_payment_collection" class="col-md-4">Auto Charge Credit Cards</label>
        <div class="col-md-4">
            <select name="mercury_auto_charge_credit_card" id="mercury_auto_charge_credit_card" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->mercury_auto_charge_credit_card   ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p>If an order comes with CC information should the software charge the credit card automatically?<br>
                    If 'No' is selected it'll create a ticket for the team to review and charge manually in case it's a
                    partial payment.</p>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="mercury_enable_email_mail" class="col-md-4">Enable Emailing/Mailing Reports To Borrowers</label>
        <div class="col-md-4">
            <select name="mercury_enable_email_mail" id="mercury_enable_email_mail" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->mercury_enable_email_mail   ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="mercury_check_proceed" class="col-md-4">Proceed with order when client sends check</label>
        <div class="col-md-4">
            <select name="mercury_check_proceed" id="mercury_check_proceed" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->mercury_check_proceed   ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7" style="text-align: center">
                <p>
                    Yes - mark order as unassigned and mark as invoiced<br>
                    No - mark as payment pending

                </p>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="mercury_excluded_loan_numbers" class="col-md-4">Mercury Excluded Loan Numbers</label>
        <div class="col-md-4">
            <textarea rows="2" cols="38" id="mercury_excluded_loan_numbers" name="mercury_excluded_loan_numbers"
                      class="form-control">{{$client->mercury_excluded_loan_numbers}}</textarea>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p>Enter loan numbers, one per line. items will be checked from the begining.<br>
                    For example Loan Numbers: 123456789, 123356789 Enter 123 to match both loan numbers 1234 to match
                    just the second one.</p>
            </div>
        </div>
    </div>

    <h2>ValuTrac Settings</h2>
    <div class="form-group row">
        <label for="valutrac_client_id" class="col-md-4">ValuTrac Client ID</label>
        <div class="col-md-4">
            <input type="text" name="valutrac_client_id" id="valutrac_client_id"
                   value="{{$client->valutrac_client_id}}"
                   class="form-control">
        </div>
    </div>
    <div class="form-group row">
        <label for="valutrac_catch_all_user_id" class="col-md-4">ValuTrac Catch All User</label>
        <div class="col-md-4">
            <input type="text" name="valutrac_catch_all_user_id" id="valutrac_catch_all_user_id"
                   value="{{$client->valutrac_catch_all_user_id}}"
                   class="form-control">
        </div>
    </div>

    <h2>FNC Settings</h2>
    <div class="form-group row">
        <label for="fnc_client_id" class="col-md-4">FNC Client ID</label>
        <div class="col-md-4">
            <input type="text" name="fnc_client_id" id="fnc_client_id"
                   value="{{$client->fnc_client_id}}"
                   class="form-control">
        </div>
    </div>
    <div class="form-group row">
        <label for="fnc_catch_all_user_id" class="col-md-4">FNC Catch All User</label>
        <div class="col-md-4">
            <input type="text" name="fnc_catch_all_user_id" id="fnc_catch_all_user_id"
                   value="{{$client->fnc_catch_all_user_id}}"
                   class="form-control">
        </div>
    </div>
    <div class="form-group row">
        <label for="fnc_enable_fee_quote" class="col-md-4">Enable Suggested Fee Quote?</label>
        <div class="col-md-4">
            <select name="fnc_enable_fee_quote" id="fnc_enable_fee_quote" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->fnc_enable_fee_quote   ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="fnc_high_value_loan_reason" class="col-md-4">High Value Loan Reason</label>
        <div class="col-md-4">
            <select name="fnc_high_value_loan_reason" id="fnc_high_value_loan_reason" class="form-control">
                <option value="0"> </option>
                @foreach($loanpurpose as $key => $value)
                    <option @if($client->fnc_high_value_loan_reason == $key) selected
                            @endif value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="fnc_send_realview" class="col-md-4">Send RealView Document?</label>
        <div class="col-md-4">
            <select name="fnc_send_realview" id="fnc_send_realview" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->fnc_send_realview   ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>

    <h2>Other Settings</h2>
    <div class="form-group row">
        <label for="integration_group_assign_keyword" class="col-md-4">Group Assign Keyword</label>
        <div class="col-md-4">
            <textarea rows="2" cols="38" id="integration_group_assign_keyword" name="integration_group_assign_keyword"
                      class="form-control">{{$client->integration_group_assign_keyword}}</textarea>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p>
                    Enter a unique keyword that if found in the other comments section when an order comes through an
                    integration<br>
                    it'll assign the order to this group if the client placing it has access it to.
                </p>
            </div>
        </div>
    </div>

    <h2>Third Party Auto Submissions</h2>
    <div class="form-group row">
        <label for="auto_submit_ucdp" class="col-md-4">Auto Submit To UCDP</label>
        <div class="col-md-4">
            <select name="auto_submit_ucdp" id="auto_submit_ucdp" class="form-control">
                <option value="0">No</option>
                ]
                <option value="1" {{$client->auto_submit_ucdp   ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p>
                    Select if you would like to automatically submit the report to UCDP. <br>
                    This will only work if UCDP enabled for the order that is being submitted.
                </p>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="auto_submit_ead" class="col-md-4">Auto Submit To EAD</label>
        <div class="col-md-4">
            <select name="auto_submit_ead" id="auto_submit_ead" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->auto_submit_ead   ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p>
                    Select if you would like to automatically submit the report to EAD.<br>
                    This will only work if EAD enabled for the order that is being submitted.
                </p>
            </div>
        </div>
    </div>
</div>
