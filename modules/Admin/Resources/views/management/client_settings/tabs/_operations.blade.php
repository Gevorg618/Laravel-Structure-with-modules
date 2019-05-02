@push('style')
    <link href="{{ masset('js/bootstrap-multiselect/dist/css/bootstrap-multiselect.css') }}" rel="stylesheet"/>
@endpush
<div class="col-md-10">
    <h2>DocuVault</h2>
    <div class="form-group row">
        <label for="enable_docuvault" class="col-md-4">Enable DocuVault</label>
        <div class="col-md-4">
            <select name="enable_docuvault" id="enable_docuvault" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->enable_docuvault ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="docuvault_require_payment" class="col-md-4">Require Up-Front Payment</label>
        <div class="col-md-4">
            <select name="docuvault_require_payment" id="docuvault_require_payment" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->docuvault_require_payment ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="docuvault_fee" class="col-md-4">DocuVault Email Fee</label>
        <div class="col-md-4">
            <input style="width:100px" type="text" data-format="ddddd" name="docuvault_fee" id="docuvault_fee"
                   value="{{$client->docuvault_fee}}" class="form-control input-medium bfh-phone">
        </div>
    </div>
    <div class="form-group row">
        <label for="send_docuvault_download_confirmation" class="col-md-4">Borrower Download Confirmation</label>
        <div class="col-md-4">
            <select name="send_docuvault_download_confirmation" id="send_docuvault_download_confirmation"
                    class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->send_docuvault_download_confirmation ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>

    <h2>AVM</h2>
    <div class="form-group row">
        <label for="enable_avm" class="col-md-4">Enable AVM</label>
        <div class="col-md-4">
            <select name="enable_avm" id="enable_avm" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->enable_avm ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="avm_require_payment" class="col-md-4">Require Up-Front Payment</label>
        <div class="col-md-4">
            <select name="avm_require_payment" id="avm_require_payment" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->avm_require_payment ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="avm_fee" class="col-md-4">AVM Email Fee</label>
        <div class="col-md-4">
            <input style="width:100px" type="text" data-format="ddddd" name="avm_fee" id="avm_fee"
                   value="{{$client->avm_fee}}" class="form-control input-medium bfh-phone">
        </div>
    </div>

    <h2>Auto Select</h2>
    <div class="form-group row">
        <label for="auto_select_enabled" class="col-md-4">Enable Auto Select</label>
        <div class="col-md-4">
            <select name="auto_select_enabled" id="auto_select_enabled" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->auto_select_enabled ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="auto_select_prefered_only" class="col-md-4">Require Only Client Preferred Appraisers</label>
        <div class="col-md-4">
            <select name="auto_select_prefered_only" id="auto_select_prefered_only" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->auto_select_prefered_only ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="auto_select_prefered_only_miles" class="col-md-4">Only Client Preferred Appraisers Maximum
            Distance</label>
        <div class="col-md-4">
            <input style="width:100px" type="text" data-format="ddddd" name="auto_select_prefered_only_miles"
                   id="auto_select_prefered_only_miles"
                   value="{{$client->auto_select_prefered_only_miles}}" class="form-control input-medium bfh-phone">
        </div>
    </div>
    <div class="form-group row col-md-12">
        <div class="span7">
            <p class="muted">
                If the setting above is set to Yes, set the maximum distance to search for client preferred appraisers
            </p>
        </div>
    </div>

    <div class="form-group row">
        <label for="appraisal_assignment_client_completed_percent" class="col-md-4">Only Client Preferred Appraisers
            Maximum
            Distance</label>
        <div class="col-md-4">
            <input style="width:100px" type="text" data-format="ddd"
                   name="appraisal_assignment_client_completed_percent"
                   id="appraisal_assignment_client_completed_percent"
                   value="0" class="form-control input-medium bfh-phone">
        </div>
    </div>

    <div class="form-group row col-md-12">
        <div class="span7">
            <p class="muted">
                Enter the percentage of completed orders by the same appraiser in order to prevent that appraiser<br>
                from receiving orders from this client once they got over this number.
            </p>
        </div>
    </div>


    <h2>Email Options</h2>
    <div class="form-group row">
        <label for="enable_client_survey" class="col-md-4">Enable Sending Survey</label>
        <div class="col-md-4">
            <select name="enable_client_survey" id="enable_client_survey" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->enable_client_survey ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="final_appr_attach_documents" class="col-md-4">Attach Documents To Email Post QC</label>
        <div class="col-md-4">
            <select name="final_appr_attach_documents" id="final_appr_attach_documents" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->final_appr_attach_documents ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="enable_qc_email_notification" class="col-md-4">Notify Client when Order enters QC Status</label>
        <div class="col-md-4">
            <select name="enable_qc_email_notification" id="enable_qc_email_notification" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->enable_qc_email_notification ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="enable_qc_correction_email_notification" class="col-md-4">Notify Client when Order enters QC
            Correction Status</label>
        <div class="col-md-4">
            <select name="enable_qc_correction_email_notification" id="enable_qc_correction_email_notification"
                    class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->enable_qc_correction_email_notification ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="support_email_addresses" class="col-md-12">Support Email Addresses</label>
        <div class="col-md-9">
            <textarea rows="6" cols="40" id="support_email_addresses" name="support_email_addresses"
                      class="form-control">{{$client->support_email_addresses}}</textarea>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p class="muted">
                    When adding log entries to an order an option to also email the log entry to a specific address will
                    <br>
                    show up. enter the different addresses the user can select from to email the log entry. Enter one
                    email <br>
                    address per line.
                </p>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="payment_confirmation_additional_emails" class="col-md-12">Payment Confirmation Email
            Addresses</label>
        <div class="col-md-9">
            <textarea rows="4" cols="30" id="payment_confirmation_additional_emails"
                      name="payment_confirmation_additional_emails"
                      class="form-control">{{$client->payment_confirmation_additional_emails}}</textarea>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p class="muted">
                    When a payment is received and a confirmation email is sent to the client, the email can also CC
                    to<br>
                    the following list of email addresses. Enter one email address per line.
                </p>
            </div>
        </div>
    </div>

    <h2>Appraiser Options</h2>
    <div class="form-group row">
        <label for="req_cert_appr" class="col-md-4">Require Certified Appraiser for all Orders</label>
        <div class="col-md-4">
            <select name="req_cert_appr" id="req_cert_appr" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->req_cert_appr   ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="req_fha_appr" class="col-md-4">Require FHA Appr for Orders</label>
        <div class="col-md-4">
            <select name="req_fha_appr" id="req_fha_appr" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->req_fha_appr   ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="min_eoins_require_each" class="col-md-4">E&O Insurance Minimums</label>
        <div class="col-md-4">
            <input style="width:100px" type="text" data-format="ddddddd" name="min_eoins_require_each"
                   id="min_eoins_require_each"
                   value="{{$client->min_eoins_require_each}}" class="form-control input-medium bfh-phone">
        </div>
    </div>

    <div class="form-group row">
        <label for="min_eoins_require_agg" class="col-md-4">Appraisers will be required to enter E&O info that meets
            the group minimums before accepting orders</label>
        <div class="col-md-4">
            <input style="width:100px" type="text" data-format="ddddddd" name="min_eoins_require_agg"
                   id="min_eoins_require_agg"
                   value="{{$client->min_eoins_require_agg}}" class="form-control input-medium bfh-phone">
        </div>
    </div>

    <div class="form-group row">
        <label for="hide_commentswhenorder" class="col-md-4">Hide Comments when Ordering</label>
        <div class="col-md-4">
            <select name="hide_commentswhenorder" id="hide_commentswhenorder" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->hide_commentswhenorder   ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>

    <h2>Client Specific Requirements</h2>
    <div class="col-md-10">
        <div class="row ">
            <div class="form-group col-md-4">
                <textarea name="standard_guidelines" id="standard_guidelines" cols="60"
                          rows="5">{{$client->standard_guidelines}}</textarea>
            </div>
        </div>
    </div>

    <div class="col-md-10">
        <h2>Submitting Lenders</h2>
        <div class="span7">
            <p class="muted">
                No Lenders Associatd With This Group.
            </p>
        </div>
    </div>

    <div class="col-md-10">
        <h2>Watch Lists</h2>
        <div class="span7">
            <p class="muted">
                Please select all the wholesale lenders this client submits to. This is a multi-select box so press
                control and click to select all lenders <br>
                that apply. This will allow us to prevent assigning to any appraisers to may be excluded from a
                'submitting lender' when the lender <br>
                selected in the order is themselves.
            </p>
        </div>
    </div>

    <div class="col-md-10" style="min-height: 300px">
        <div class="row">
            <div class="form-group row">
                <div class="col-md-4">
                    <?php   $lenders_used = array_flip(explode(',', $client->lenders_used)); ?>
                    <select name="lenders_used[]" id="lenders_used"
                            class="form-control multiselect bootstrap-multiselect" multiple="multiple">
                        @foreach($lenderList as $key => $value)
                            <option
                                {{isset($lenders_used[$key]) ? 'selected' : '' }}  value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ masset('js/bootstrap-multiselect/dist/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/management/client_settings/edit.js') }}"></script>
@endpush

