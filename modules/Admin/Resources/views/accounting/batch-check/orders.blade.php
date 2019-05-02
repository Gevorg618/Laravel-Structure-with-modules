<h2>Found {!! count($rows) !!} Orders <span id='selectd-orders'>0</span> Selected.</h2>
<h4 style="margin-top:0px;margin-bottom:0px;">Amount Charged $<span id='selectd-orders-paid'>0</span></h4>

<hr>
<div class="table-responsive">
    <div id="tabs">
        <ul class="nav nav-tabs" id="daily_batch_tab">
            <li class="active"><a data-toggle="tab" href="#check">Check</a>
            </li>
            <li><a data-toggle="tab" id="credit_card_tab" href="#credit_card">Appraisal Checks</a></li>
        </ul>
        <div class="tab-content">
            <div id="check" class="tab-pane fade in active">
                <hr>
                {!! Form::open([
                    'route' => ['admin.accounting.batch-check.apply-batch-check'],
                    'id' => 'apply_batch_check_form',
                    'class' => 'form-horizontal',
                    'method' => 'POST'
                ]) !!}
                <div class="form-group">
                    <label for="date" class="col-md-2 control-label">Date Received (YYYY-MM-DD)</label>

                    <div class="col-md-6">
                        {!! Form::text('date', null,
                            ['id' => 'date', 'class' => 'datepicker form-control', 'placeholder' => 'Date Received']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="check_number" class="col-md-2 control-label">Check Number</label>
                    <div class="col-md-6">
                        {!! Form::text('check_number', null,
                            ['id' => 'check_number', 'class' => 'form-control', 'placeholder' => 'Check Number']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="from" class="col-md-2 control-label">Received</label>
                    <div class="col-md-6">
                        {!! Form::text('from', null,
                            ['id' => 'from', 'class' => 'form-control', 'placeholder' => 'Received From']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="type" class="col-md-2 control-label">Type</label>
                    <div class="col-md-6">
                        {!! Form::select('check_type', $checkTypes, null,
                            ['id' => 'check_type', 'class' => 'form-control', 'placeholder' => 'Choose Type']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="additional" class="col-md-2 control-label">Accept As Additional Payment</label>
                    <div class="col-md-6">
                        {!! Form::checkbox('additional', 1, null,
                            ['id' => 'additional', 'class' => 'form-control']
                        ) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-10">
                        <input type="submit" value=' Apply Check ' class='btn btn-success' name='apply_batch_check'
                               id='apply_batch_check'/>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div id="credit_card" class="tab-pane fade">
                <hr>
                <div class="form-group">
                    <strong>Please read the following statement to the person making payment prior
                        to charging the card:</strong>
                    <br/>By submitting this payment, You authorize Landmark Network, Inc. to charge
                    the provided credit card for the amount disclosed.
                    You understand that this amount is to cover all costs associated with the
                    completion of an appraisal and this payment is not contingent upon any
                    pre-determined or expected value outcome.
                </div>
                {!! Form::open([
                    'route' => ['admin.accounting.batch-check.apply-batch-cc'],
                    'id' => 'apply_batch_cc_form',
                    'class' => 'form-horizontal',
                    'method' => 'POST'
                ]) !!}
                <div class="form-group">
                    <label for="firstname" class="col-md-2 control-label">First Name</label>

                    <div class="col-md-6">
                        {!! Form::text('firstname', null,
                            ['id' => 'firstname', 'class' => 'datepicker form-control', 'placeholder' => 'First Name']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastname" class="col-md-2 control-label">Last Name</label>
                    <div class="col-md-6">
                        {!! Form::text('lastname', null,
                            ['id' => 'lastname', 'class' => 'form-control', 'placeholder' => 'Last Name']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="address" class="col-md-2 control-label">Address</label>
                    <div class="col-md-6">
                        {!! Form::text('address', null,
                            ['id' => 'address', 'class' => 'form-control', 'placeholder' => 'Address']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="city" class="col-md-2 control-label">City</label>
                    <div class="col-md-6">
                        {!! Form::text('city', null,
                            ['id' => 'city', 'class' => 'form-control', 'placeholder' => 'City']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="type" class="col-md-2 control-label">State</label>
                    <div class="col-md-6">
                        {!! Form::select('state', getStates(), null,
                            ['id' => 'state', 'class' => 'form-control', 'placeholder' => 'Choose State']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="zip" class="col-md-2 control-label">ZIP</label>
                    <div class="col-md-6">
                        {!! Form::text('zip', null,
                            ['id' => 'zip', 'class' => 'form-control', 'placeholder' => 'ZIP']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="card_number" class="col-md-2 control-label">Card Number</label>
                    <div class="col-md-6">
                        {!! Form::text('card_number', null,
                            ['id' => 'card_number', 'class' => 'form-control', 'placeholder' => 'Card Number']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="card_exp_month" class="col-md-2 control-label">Card Expiration Month</label>
                    <div class="col-md-6">
                        {!! Form::text('card_exp_month', null,
                            ['id' => 'card_exp_month', 'class' => 'form-control', 'placeholder' => 'Card Expiration Month']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="card_exp_year" class="col-md-2 control-label">Card Expiration Year</label>
                    <div class="col-md-6">
                        {!! Form::text('card_exp_year', null,
                            ['id' => 'card_exp_year', 'class' => 'form-control', 'placeholder' => 'Card Expiration Year']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="card_cvv" class="col-md-2 control-label">Card Verification</label>
                    <div class="col-md-6">
                        {!! Form::password('card_cvv',
                            ['id' => 'card_cvv', 'class' => 'form-control', 'placeholder' => 'Card CVV']
                        ) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-10">
                        <input type="submit" value=' Apply Check ' class='btn btn-success' name='apply_batch_cc'
                               id='apply_batch_cc'/>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

</form>

<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th style="width:1px;"><input type="checkbox" name="select_all_orders" style="width:20px;"></th>
        <th>Date Delivered</th>
        <th>Invoice #</th>
        <th>Borrower</th>
        <th>Group</th>
        <th>Address</th>
        <th>Invoice Amount</th>
        <th>Paid Amount</th>
        <th>Charge Amount</th>
    </tr>
    </thead>
    <tbody>
    @if(count($rows))
        @foreach ($rows as $row)
            <tr class="order-tr-row">
                <td><input type="checkbox" name="orders[]" value="{{ $row['id'] }}" class="order-checkbox"
                           style="width:20px;"></td>
                <td>{!! $row['date_delivered'] !!}</td>
                <td><a href="/admin/order.php?oid={{ $row['id'] }}" target="_blank">{!! $row['invoice'] !!}</a></td>
                <td>{!! $row['borrower'] !!}</td>
                <td>{!! $row['group'] !!}</td>
                <td>{!! $row['address'] !!}</td>
                <td class="invoice">${!! $row['invoicedue'] !!}</td>
                <td class="paid">${!! $row['paid_amount'] !!}</td>
                <td><input name="orders_amount[{{ $row['id'] }}]" id="orders_amount_{{ $row['id'] }}" value="{{ $row['invoicedue']-$row['paid_amount'] }}"
                           class="amount-charge-val" style="width:50px;"></td>
            </tr>
        @endforeach
        <tr>
            <th colspan="6">&nbsp;</th>
            <th>${!! number_format($totals['invoicedue'], 2) !!}</th>
            <th>${!! number_format($totals['paid_amount'], 2) !!}</th>
            <th>&nbsp;</th>
        </tr>

        <tr>
            <th colspan="6">&nbsp;</th>
            <th colspan="2">Outstanding: ${!! number_format($totals['invoicedue']-$totals['paid_amount'], 2) !!}</th>
            <th>&nbsp;</th>
        </tr>
    @else
        <tr>
            <td colspan="9">No Records Found!</td>
        </tr>
    @endif
    </tbody>
</table>