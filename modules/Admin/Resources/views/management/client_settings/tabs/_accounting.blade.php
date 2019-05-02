<div class="col-md-10">
    <h2>Invoicing Options</h2>
    <div class="form-group row">
        <label for="net_days" class="col-md-4">Appraisal Account is NET</label>
        <div class="col-md-4">
            <select id="net_days" name="net_days" class="form-control">
                <option value="DNB">Do Not Bill</option>
                <option value="15" {{$client->net_days == '15'   ? 'selected' : ''}}>15 Days</option>
                <option value="30" {{$client->net_days == '30'   ? 'selected' : ''}}>30 Days</option>
                <option value="45" {{$client->net_days == '45'   ? 'selected' : ''}}>45 Days</option>
                <option value="60" {{$client->net_days == '60'   ? 'selected' : ''}}>60 Days</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="valusync_net_days" class="col-md-4">Alternative Valuation Account is NET</label>
        <div class="col-md-4">
            <select id="valusync_net_days" name="valusync_net_days" class="form-control">
                <option value="DNB">Do Not Bill</option>
                <option value="15" {{$client->valusync_net_days == '15'   ? 'selected' : ''}}>15 Days</option>
                <option value="30" {{$client->valusync_net_days == '30'   ? 'selected' : ''}}>30 Days</option>
                <option value="45" {{$client->valusync_net_days == '45'   ? 'selected' : ''}}>45 Days</option>
                <option value="60" {{$client->valusync_net_days == '60'   ? 'selected' : ''}}>60 Days</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="enable_auto_ar" class="col-md-4">Enable Auto Email Accounts Receivable Report</label>
        <div class="col-md-4">
            <select name="enable_auto_ar" id="enable_auto_ar" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->enable_auto_ar   ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="auto_ar_emails" class="col-md-4">Auto Email Accounts Receivable Report Email Addresses</label>
        <div class="col-md-4">
            <textarea rows="2" cols="38" id="auto_ar_emails" name="auto_ar_emails"
                      class="form-control">{{$client->auto_ar_emails}}</textarea>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p>Enter email addresses that will receivable a monthly statement. one per line</p>
            </div>
        </div>
    </div>

    <h2>Company Information</h2>
    <div class="form-group row">
        <label for="notify_order_placed_subject" class="col-md-4">Company Name</label>
        <div class="col-md-4">
            <input type="text" name="ap_company" id="ap_company"
                   value="{{$client->ap_company}}"
                   class="form-control">
        </div>
    </div>
    <div class="form-group row">
        <label for="ap_address1" class="col-md-4">Company Address</label>
        <div class="col-md-4">
            <input type="text" name="ap_address1" id="ap_address1"
                   value="{{$client->ap_address1}}"
                   class="form-control">
            <input type="text" name="ap_address2" id="ap_address2"
                   value="{{$client->ap_address2}}"
                   class="form-control" style="margin-top: 10px;" placeholder="Suite, Floor, etc...">
        </div>
    </div>
    <div class="form-group row">
        <label for="notify_order_placed_subject" class="col-md-4">Company City</label>
        <div class="col-md-4">
            <input type="text" name="ap_city" id="ap_city"
                   value="{{$client->ap_city}}"
                   class="form-control">
        </div>
    </div>
    <div class="form-group row">
        <label for="ap_state" class="col-md-4">Company State, Zip</label>
        <div class="col-md-4" style="overflow:hidden">
            <select id="ap_state" name="ap_state" class="form-control" style="width:160px; float: left">
                <option value="">-- State --</option>
                @foreach(getStates() as $key => $value)
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
            <input data-format="ddddd - dddd" type="text" name="ap_zip" id="ap_zip"
                   value="{{$client->ap_zip}}"
                   class="form-control input-medium bfh-phone" style="width: 155px; float: left; margin-left: 10px">
        </div>
    </div>
    <h2>Primary Contact Information</h2>
    <div class="form-group row">
        <label for="ap_contact" class="col-md-4">Name</label>
        <div class="col-md-4">
            <input type="text" name="ap_contact" id="ap_contact"
                   value="{{$client->ap_contact}}"
                   class="form-control">
        </div>
    </div>
    <div class="form-group row">
        <label for="ap_phone" class="col-md-4">Phone</label>
        <div class="col-md-4">
            <input type="number" name="ap_phone" id="ap_phone"
                   value="{{$client->ap_phone}}"
                   class="form-control input-medium bfh-phone" data-format="(ddd)-ddd-ddddxddddd">
        </div>
    </div>
    <div class="form-group row">
        <label for="ap_email" class="col-md-4">Email</label>
        <div class="col-md-4">
            <input type="email" name="ap_email" id="ap_email"
                   value="{{$client->ap_email}}"
                   class="form-control input-medium">
        </div>
    </div>
    <div class="form-group row">
        <label for="ap_statementemails" class="col-md-4">Additional Emails for Statements</label>
        <div class="col-md-4">
            <textarea rows="2" cols="38" id="ap_statementemails" name="ap_statementemails"
                      class="form-control">{{$client->ap_statementemails}}</textarea>
        </div>
        <div class="col-md-12">
            <div class="span7" style="text-align: center">
                <p>separate emails with commas</p>
            </div>
        </div>
    </div>

    <h2>Credit Card Information</h2>
    <div class="form-group row">
        <label for="cc_name" class="col-md-4">Card Holder Name</label>
        <div class="col-md-4">
            <input type="text" name="cc_name" id="cc_name"
                   value="{{$client->cc_name}}"
                   class="form-control">
        </div>
    </div>
    <div class="form-group row">
        <label for="cc_number" class="col-md-4">Card Number</label>
        <div class="col-md-4">
            <input type="text" name="cc_number" id="cc_number"
                   value="{{$client->cc_number}}"
                   class="form-control">
        </div>
    </div>

    <div class="form-group row">
        <?php
        $expExplode = explode('-', $client->cc_exp);
        if ($expExplode[0] && $expExplode[1]) {
            $expMonth = $expExplode[0];
            $expYear = $expExplode[1];
        }
        ?>
        <label for="cc_exp_month" class="col-md-4">Card Expiration</label>
        <div class="col-md-6" style="overflow:hidden">
            <select id="cc_exp_month" name="cc_exp_month" class="form-control" style="width: 155px; float: left;">
                <option value="">--</option>
                @for ($i = 1; $i <= 12; $i++)
                    @if($i <= 9)
                        <option @if(isset($expMonth) == $i) selected @endif value="0{{$i}}">0{{$i}}</option>
                    @else
                        <option @if(isset($expMonth) == $i) selected @endif value="{{$i}}">{{$i}}</option>
                    @endif
                @endfor
            </select>
            <select id="cc_exp_year" name="cc_exp_year" class="form-control"
                    style="width: 155px; float: left; margin-left: 16px">
                <option value="">--</option>
                @for($i=date('Y');$i<=(date('Y')+10);$i++)
                    <option @if(isset($expYear) == $i) selected @endif  value="{{$i}}">{{$i}}</option>
                @endfor
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="cc_billing_address" class="col-md-4">Card Billing Address</label>
        <div class="col-md-4">
            <input type="text" name="cc_billing_address" id="cc_billing_address"
                   value="{{$client->cc_billing_address}}"
                   class="form-control">
            <input type="text" name="cc_billing_address2" placeholder="Suite, Floor, etc..." id="cc_billing_address2"
                   value="{{$client->cc_billing_address2}}"
                   class="form-control" style="margin-top: 10px">
        </div>
    </div>

    <div class="form-group row">
        <label for="cc_billing_city" class="col-md-4">Card Billing City</label>
        <div class="col-md-4">
            <input type="text" name="cc_billing_city" id="cc_billing_city"
                   value="{{$client->cc_billing_city}}"
                   class="form-control">
        </div>
    </div>

    <div class="form-group row">
        <label for="cc_billing_state" class="col-md-4">Card Billing State, Zip</label>
        <div class="col-md-4" style="overflow:hidden">
            <select id="cc_billing_state" name="cc_billing_state" class="form-control" style="width:160px; float: left">
                <option value="">-- State --</option>
                @foreach(getStates() as $key => $value)
                    <option @if($client->cc_billing_state == $key) selected @endif value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
            <input data-format="ddddd - dddd" type="text" name="cc_billing_zip" id="cc_billing_zip"
                   value="{{$client->cc_billing_zip}}"
                   class="form-control input-medium bfh-phone" style="width: 155px; float: left; margin-left: 10px">
        </div>
    </div>

    <h2>AP Logs</h2>
    <div class="form-group">
        <div class="col-md-4">
            <label for="log_order_ids">Order IDs</label>
            <input type="text"  id="log_order_ids"
                    class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-4">
            <label for="log_message">Message</label>
            <textarea type="text" id="log_message"
                      class="form-control"> </textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6">
            <button type="button" class="btn btn-default" id="add_ap_log">Add Log</button>
        </div>

    </div>
    <div id="ap_logs_div">
        <div class="logs_table_overflow">
            <table class="table table-bordered">
                <thead class="borderless">
                <tr>
                    <th>Date Created</th>
                    <th>Created By</th>
                    <th>Message</th>
                </tr>
                </thead>
                <tbody class="borderless">
                    @foreach($apLogs as $apLog)
                        <tr>
                            <td>{{date('m/d/Y H:i', $apLog->created_date)}}</td>
                            <td>{{getUserFullNameById($apLog->created_by)}}</td>
                            <td>{{$apLog->message}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <input type="hidden" value="{{route('admin.management.client.add.ap.log')}}" id="ap_log_url">
    <input type="hidden" value="{{$client->id}}" id="ap_log_group_id">
</div>
@push('scripts')
    <script src="{{ masset('js/management/client_settings/ap_logs.js') }}"></script>
@endpush
