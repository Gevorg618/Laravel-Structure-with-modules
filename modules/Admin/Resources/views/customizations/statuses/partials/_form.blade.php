
<div class="row">
    <div class="form-group col-lg-12">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">General</a></li>
            <li role="presentation"><a href="#auto_email" aria-controls="auto_email" role="tab" data-toggle="tab">Automated Emails</a></li>
        </ul>
    </div>
</div>
     
<div class="form-body" style="margin-top: 50px;">

    <div class="row">
        <div class="col-lg-12">
            <!-- Tab panes -->
            <div class="tab-content">

                <div role="tabpanel" class="tab-pane active" id="general">

                    <div class="form-group col-md-6">
                        <label name="descrip" class="control-label col-lg-3 col-xs-12">Title
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-lg-12 col-xs-12">
                            {!! Form::text('descrip', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label name="client_title" class="control-label col-lg-12 col-xs-12">Client Title
                        </label>
                        <div class="col-lg-12 col-xs-12">
                            {!! Form::text('client_title', null, ['class' => 'form-control']) !!}
                            <span class="help-block client_title-error-block"></span>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label name="appraiser_title" class="control-label col-lg-12 col-xs-12">Appraiser Title
                        </label>
                        <div class="col-lg-12 col-xs-12">
                            {!! Form::text('appraiser_title', null, ['class' => 'form-control']) !!}
                            <span class="help-block appraiser_title-error-block"></span>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label name="status_select_order" class="control-label col-lg-12 col-xs-12">Status Select Order
                        </label>
                        <div class="col-lg-12 col-xs-12">
                            {!! Form::text('status_select_order', null, ['class' => 'form-control']) !!}
                            <span class="help-block status_select_order-error-block"></span>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label name="admin_message" class="control-label col-lg-12 col-xs-12">Admin Message
                        </label>
                        <div class="col-lg-12 col-xs-12">
                            {!! Form::textarea('admin_message', null, ['class' => 'form-control']) !!}
                            <span class="help-block admin_message-error-block"></span>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label name="client_message" class="control-label col-lg-12 col-xs-12">Client Message
                        </label>
                        <div class="col-lg-12 col-xs-12">
                            {!! Form::textarea('client_message', null, ['class' => 'form-control']) !!}
                            <span class="help-block client_message-error-block"></span>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label name="appraiser_message" class="control-label col-lg-12 col-xs-12">Appraiser Message
                        </label>
                        <div class="col-lg-12 col-xs-12">
                            {!! Form::textarea('appraiser_message', null, ['class' => 'form-control']) !!}
                            <span class="help-block appraiser_message-error-block"></span>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label name="block_appraiser_actions" class="control-label col-lg-12 col-xs-12">Block Appraiser from performing actions while the order in this status?
                        </label>
                        <div class="col-lg-12 col-xs-12">
                            {!! Form::checkbox('block_appraiser_actions', 1, null, []) !!}
                            <span class="help-block block_appraiser_actions-error-block"></span>
                        </div>
                    </div>


                </div>

                <div role="tabpanel" class="tab-pane" id="auto_email">
                    <div class="panel panel-primary">

                        <div class="panel-heading">
                            <h3 class="panel-title">Vendor Automated Status Email</h3>
                        </div>
                          
                        <div class="panel-body">
                            <div class="row">

                                <div class="form-group col-md-6">
                                    <label name="vendor_auto_email_enable" class="control-label col-lg-3 col-xs-12"> Enable
                                    </label>
                                    <div class="col-lg-12 col-xs-12">
                                        {{ Form::select('vendor_auto_email_enable', [0 => 'No', 1 => 'Yes'], null, ['class' => 'form-control']) }}
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label name="vendor_auto_email_subject" class="control-label col-lg-12 col-xs-12">Subject
                                    </label>
                                    <div class="col-lg-12 col-xs-12">
                                        {!! Form::text('vendor_auto_email_subject', null, ['class' => 'form-control']) !!}
                                        <span class="help-block vendor_auto_email_subject-error-block">Enter the vendor email subject line. you can use the replaceable keys ({...})</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label name="vendor_auto_email_revisit_date" class="control-label col-lg-12 col-xs-12">Set Revisit Date (+x Days)
                                    </label>
                                    <div class="col-lg-12 col-xs-12">
                                        {!! Form::text('vendor_auto_email_revisit_date', null, ['class' => 'form-control']) !!}
                                        <span class="help-block vendor_auto_email_revisit_date-error-block">Enter a number of days to set a revisit date in the future. if a number is entered and a revisit date is set, the vendor will not get any future automated emails until that revisit date is reached.</span>
                                    </div>
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label name="vendor_auto_email_once_every_days" class="control-label col-lg-12 col-xs-12">Email Once every x days
                                    </label>
                                    <div class="col-lg-12 col-xs-12">
                                        {!! Form::text('vendor_auto_email_once_every_days', null, ['class' => 'form-control']) !!}
                                        <span class="help-block vendor_auto_email_once_every_days-error-block">Enter a number of days to check and run this automated email. For example if set to 1 it'll run every day, 2 will run every other day 3 will run every three days etc.. if a revisit date is set it'll skip this automated email regardless of the number set in this field. For example if the above field for revisit date is set to 7 and this field is set to 1 it'll run once, set revisit date in 7 days and the next time it'll run again and email the vendor will be once those 7 days have passed and the current date and time reached the revisit date set 7 days ago.</span>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label name="vendor_auto_email_min_days_in_status" class="control-label col-lg-12 col-xs-12">Min Days In Status
                                    </label>
                                    <div class="col-lg-12 col-xs-12">
                                        {!! Form::text('vendor_auto_email_min_days_in_status', null, ['class' => 'form-control']) !!}
                                        <span class="help-block vendor_auto_email_min_days_in_status-error-block">Enter the number of days an order needs to sit in this status for the automated email to be sent out to the Vendor. 0 Means the first day it enters the status, 1 means the order has to be in that status for at least one day etc..</span>
                                    </div>
                                </div>

                                <div class="form-group col-md-12 ">
                                    <div class="col-lg-12 col-xs-12">
                                        {{ Form::textarea('vendor_auto_email_text', null, ['class' => 'form-control']) }}
                                        <span class="help-block vendor_auto_email_text-error-block"></span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>   


                    <div class="panel panel-warning">

                        <div class="panel-heading">
                            <h3 class="panel-title">Client Automated Status Email</h3>
                        </div>
                          
                        <div class="panel-body">
                            <div class="row">

                                <div class="form-group col-md-6">
                                    <label name="client_auto_email_enable" class="control-label col-lg-3 col-xs-12"> Enable
                                    </label>
                                    <div class="col-lg-12 col-xs-12">
                                        {{ Form::select('client_auto_email_enable', [0 => 'No', 1 => 'Yes'], null, ['class' => 'form-control']) }}
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label name="client_auto_email_subject" class="control-label col-lg-12 col-xs-12">Subject
                                    </label>
                                    <div class="col-lg-12 col-xs-12">
                                        {!! Form::text('client_auto_email_subject', null, ['class' => 'form-control']) !!}
                                        <span class="help-block client_auto_email_subject-error-block">Enter the vendor email subject line. you can use the replaceable keys ({...})</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label name="client_auto_email_revisit_date" class="control-label col-lg-12 col-xs-12">Set Revisit Date (+x Days)
                                    </label>
                                    <div class="col-lg-12 col-xs-12">
                                        {!! Form::text('client_auto_email_revisit_date', null, ['class' => 'form-control']) !!}
                                        <span class="help-block client_auto_email_revisit_date-error-block">Enter a number of days to set a revisit date in the future. if a number is entered and a revisit date is set, the vendor will not get any future automated emails until that revisit date is reached.</span>
                                    </div>
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label name="client_auto_email_once_every_days" class="control-label col-lg-12 col-xs-12">Email Once every x days
                                    </label>
                                    <div class="col-lg-12 col-xs-12">
                                        {!! Form::text('client_auto_email_once_every_days', null, ['class' => 'form-control']) !!}
                                        <span class="help-block client_auto_email_once_every_days-error-block">Enter a number of days to check and run this automated email. For example if set to 1 it'll run every day, 2 will run every other day 3 will run every three days etc.. if a revisit date is set it'll skip this automated email regardless of the number set in this field. For example if the above field for revisit date is set to 7 and this field is set to 1 it'll run once, set revisit date in 7 days and the next time it'll run again and email the vendor will be once those 7 days have passed and the current date and time reached the revisit date set 7 days ago.</span>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label name="client_auto_email_min_days_in_status" class="control-label col-lg-12 col-xs-12">Min Days In Status
                                    </label>
                                    <div class="col-lg-12 col-xs-12">
                                        {!! Form::text('client_auto_email_min_days_in_status', null, ['class' => 'form-control']) !!}
                                        <span class="help-block client_auto_email_min_days_in_status-error-block">Enter the number of days an order needs to sit in this status for the automated email to be sent out to the Vendor. 0 Means the first day it enters the status, 1 means the order has to be in that status for at least one day etc..</span>
                                    </div>
                                </div>

                                <div class="form-group col-md-12 ">
                                    <div class="col-lg-12 col-xs-12">
                                        {{ Form::textarea('client_auto_email_text', null, ['class' => 'form-control']) }}
                                        <span class="help-block vendor_auto_email_text-error-block"></span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>                
            </div>
            
        </div>
        
    </div>
    <div class="form-group col-md-12">
        <div class="col-lg-6 col-xs-12">
            <button type="submit" class="btn btn-primary">{{ $button_label }}</button>          
        </div>
    </div>
</div>