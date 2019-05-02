<div class="row">
    <div class="span8">
        <h4 class="text-info">Credit Card Information</h4>
        <div class="alert alert-info">
            This is used to charge a fee for orders assigned to you. If the Lender/AMC wishes to pass on the fee charge to the Appraiser assigned. The software will charge a fee on the credit card that is on file. If there is no credit card on file you might not be able to get any orders assigned to you.
        </div>
        <div class="alert alert-warning">
            Unless you have been asked to provide your credit card information for billing purposes, please do not fill out this form.
        </div>

    </div>
</div>


<div class="row">
    <div class="span8">
        <h4 class="text-info">Current Credit Card</h4>
        <div id='current-cc-card'>
            @include('users.partials.current_cc_card')
        </div>
    </div>
</div>

<div class="row">
    <div class="panel panel-primary">
        <div class="panel-heading">Add New Credit Card </div>
        <div class="panel-body">
            <div class="alert alert-error">Please enter only letters and numbers, no middle initials, no special characters.</div>
            <div class="row">
                <div class="span4">
                    <div class="control-group" style="margin-bottom: 0px;">
                        <label class="control-label">First Name</label>
                        {!! Form::text('cc_firstname', Request::get('cc_firstname'), ['size' => 20, 'class' => 'cc_info form-control', 'id' => 'cc_firstname']) !!}
                    </div>
                    <div class="control-group" style="margin-bottom: 0px;">
                        <label class="control-label">Last Name</label>
                        {!! Form::text('cc_lastname', Request::get('cc_lastname'), ['size' => 20, 'class' => 'cc_info form-control', 'id' => 'cc_lastname']) !!}
                    </div>

                    <div class="control-group" style="margin-bottom: 0px;">
                        <label class="control-label">Billing Address</label>
                        {!! Form::text('cc_address', Request::get('cc_address'), ['size' => 20, 'class' => 'cc_info form-control', 'id' => 'cc_address']) !!}
                    </div>

                    <div class="control-group" style="margin-bottom: 0px;">
                        <label class="control-label">Billing City</label>
                        {!! Form::text('cc_city', Request::get('cc_city'), ['size' => 20, 'class' => 'cc_info form-control', 'id' => 'cc_city']) !!}
                    </div>

                    <div class="control-group" style="margin-bottom: 0px;">
                        <label class="control-label" style="width:170px;">State, Zip</label>
                        <div class="row">
                            <div class="span2">
                                {!! Form::select('cc_state', getStates(), Request::get('cc_state'), ['style' => 'width:130px;float:left', 'placeholder' => 'Choose state', 'class' => 'cc_info form-control', 'id' => 'cc_state']) !!}
                            </div>
                            <div class="span1">
                                {!! Form::text('cc_zip', Request::get('cc_zip'), ['size' => '20', 'style' => 'width:85px;margin-left:5px;', 'class' => 'zipcode cc_info form-control', 'id' => 'cc_zip']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="span4">
                    <div class="control-group" style="margin-bottom: 0px;">
                        <label class="control-label">Credit Card Number</label>
                        {!! Form::text('cc_number', Request::get('cc_number'), ['size' => '20', 'class' => 'cc-number cc_info form-control', 'id' => 'cc_number']) !!}
                    </div>
                    <div class="control-group" style="margin-bottom: 0px;">
                        <label class="control-label" style="width:170px;">Card Expiration (MM/YY)</label>
                        <div class="row">
                            <div class="span2">
                                {!! Form::text('cc_exp_month', Request::get('cc_exp_month'), ['size' => '5', 'style' => 'width:40px;float:left',  'maxlength' => '2', 'class' => 'cc_info form-control', 'id' => 'cc_exp_month']) !!}
                                {!! Form::text('cc_exp_year', Request::get('cc_exp_year'), ['size' => '5', 'style' => 'width:40px;margin-left:5px;', 'maxlength' => '2', 'class' => 'cc_info form-control', 'id' => 'cc_exp_year']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="control-group" style="margin-bottom: 0px;">
                        <label>Card CVV Number</label>
                        <div class="row">
                            <div class="span2">
                                {!! Form::text('cc_cvv', Request::get('cc_cvv'), ['size' => '5', 'style' => 'width:40px;float:left',  'maxlength' => '4', 'class' => 'cc_info form-control', 'id' => 'cc_cvv']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button type="button" id="add-card" name="add-card" value="Add Card" class="btn btn-primary">Add Card</button>
        </div>
    </div>
</div>

