<div class="panel-body panel-body-table">
    <h2>Found {!! count($rows) !!} Orders <span id='selectd-orders'>0</span> Selected.</h2>
    <h4 style="margin-top:0px;margin-bottom:0px;">Amount Charged $<span id='selectd-orders-paid'>0</span></h4>
    <hr>

    <div class="row">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#check">Check</a></li>
            <li><a data-toggle="tab" href="#credit_card">Credit Card</a></li>
        </ul>

        <div class="tab-content">
            <div id="check" class="tab-pane fade in active">
                <hr>
                {!! Form::open([
            'route' => ['admin.accounting.batch-docuvault-check.apply-batch-check'],
            'id' => 'apply_batch_check_docuvault_form',
            'class' => 'form-horizontal',
            'method' => 'GET'
        ]) !!}
                <div class="form-group">
                    <label for="date" class="col-md-2 control-label">Date Received (YYYY-MM-DD)</label>

                    <div class="col-md-2">
                        {!! Form::text('date', null,
                            ['id' => 'date', 'class' => 'datepicker form-control', 'placeholder' => 'Date']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="check_number" class="col-md-2 control-label">Check Number</label>

                    <div class="col-md-2">
                        {!! Form::text('check_number', null,
                            ['id' => 'check_number', 'class' => 'form-control', 'placeholder' => 'Check Number']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="from" class="col-md-2 control-label">Received From</label>

                    <div class="col-md-2">
                        {!! Form::text('from', null,
                            ['id' => 'from', 'class' => 'form-control', 'placeholder' => 'Received From']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="ordertype" class="col-md-2 control-label">Date To</label>
                    <div class="col-md-2">
                        {!! Form::select('type', $types, null, [
                            'class' => 'form-control',
                            'placeholder' => 'Choose check type',
                            'id' => 'type',
                        ]) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-10">
                        <button type="submit" value="submit" name="submit" class="btn btn-primary">Apply Check
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div id="credit_card" class="tab-pane fade">
                <hr>
                <div class="span2">
                    <img class="card" src="/images/cc_accepted.png" alt="Accepted Cards">
                </div>
                <div class="span10">
                    <strong>Please read the following statement to the person making payment prior to charging the
                        card:</strong>
                    <br/>By submitting this payment, You authorize Landmark Network, Inc. to charge the provided credit
                    card for the amount disclosed.
                    You understand that this amount is to cover all costs associated with the completion of an appraisal
                    and this payment is not contingent upon any pre-determined or expected value outcome.
                </div>
                <hr>
                {!! Form::open([
            'route' => ['admin.accounting.batch-docuvault-check.apply-batch-cc-check'],
            'id' => 'apply_batch_docuvault_check_cc_form',
            'class' => 'form-horizontal',
            'method' => 'GET'
        ]) !!}
                <div class="form-group">
                    <label for="first_name" class="col-md-2 control-label">First Name</label>

                    <div class="col-md-2">
                        {!! Form::text('first_name', null,
                            ['id' => 'first_name', 'class' => 'form-control', 'placeholder' => 'First Name']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="last_name" class="col-md-2 control-label">Last Name</label>

                    <div class="col-md-2">
                        {!! Form::text('last_name', null,
                            ['id' => 'last_name', 'class' => 'form-control', 'placeholder' => 'Last Name']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="address" class="col-md-2 control-label">Billing Address</label>

                    <div class="col-md-2">
                        {!! Form::text('address', null,
                            ['id' => 'address', 'class' => 'form-control', 'placeholder' => 'Address']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="address" class="col-md-2 control-label">City</label>

                    <div class="col-md-2">
                        {!! Form::text('city', null,
                            ['id' => 'city', 'class' => 'form-control', 'placeholder' => 'City']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="address" class="col-md-2 control-label">State</label>

                    <div class="col-md-2">
                        {!! Form::select('type', $states, null, [
                            'class' => 'form-control',
                            'placeholder' => 'Choose state',
                            'id' => 'state',
                        ]) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="address" class="col-md-2 control-label">ZIP</label>

                    <div class="col-md-2">
                        {!! Form::text('zip', null,
                            ['id' => 'zip', 'class' => 'form-control', 'placeholder' => 'ZIP']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="card_number" class="col-md-2 control-label">Credit Card Number</label>

                    <div class="col-md-2">
                        {!! Form::text('card_number', null,
                            ['id' => 'card_number', 'class' => 'form-control', 'placeholder' => 'Credit Card Number']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="card_exp" class="col-md-2 control-label">Card Expiration (MM/YY)</label>

                    <div class="col-md-2">
                        {!! Form::text('card_exp_month', null,
                            ['id' => 'card_exp_month', 'class' => 'form-control', 'placeholder' => 'Card Expiration Month']
                        ) !!}
                        {!! Form::text('card_exp_year', null,
                            ['id' => 'card_exp_year', 'class' => 'form-control', 'placeholder' => 'Card Expiration Year']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="card_cvv" class="col-md-2 control-label">Card Verification Number</label>

                    <div class="col-md-2">
                        {!! Form::text('card_cvv', null,
                            ['id' => 'card_cvv', 'class' => 'form-control', 'placeholder' => 'Credit Card CVV']
                        ) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-10">
                        <button type="submit" value="submit" name="submit" class="btn btn-primary">Apply Credit Card
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="order-rows">
                <thead>
                <th style="width:1px;"><input type="checkbox" id="select_all_orders" style="width: 20px"/></th>
                <th>Notification Sent</th>
                <th>Order #</th>
                <th>Borrower</th>
                <th>Group</th>
                <th>Address</th>
                <th>Invoice Amount</th>
                <th>Paid Amount</th>
                <th>Charge Amount</th>
                </thead>
                <tbody>
                @if(count($rows))
                    @foreach($rows as $row)
                    <tr class="order-tr-row">
                        <td><input type="checkbox" class="order-checkbox" name="orders[]" value="{!! $row['id'] !!}" style="width: 20px"></td>
                        <td>{!!$row['notification_sent'] !!}</td>
                        <td>
                            @if($orderType == 'docuvault')
                                <a href="/docuvaultorder.php?oid={!! $row['id'] !!}"
                                   target="_blank">{!! $row['invoice'] !!}</a>
                            @else
                                <a href="/admin/order.php?oid={!! $row['id'] !!}"
                                   target="_blank">{!! $row['invoice'] !!}</a>
                            @endif
                        </td>
                        <td>{!! $row['borrower'] !!}</td>
                        <td>{!! $row['group'] !!}</td>
                        <td>{!! $row['address'] !!}</td>
                        <td class="invoice">${!! $row['invoicedue'] !!}</td>
                        <td class="paid">${!! $row['paid_amount'] !!}</td>
                        <td><input id="orders_amount_{!! $row['id'] !!}" type="text" name="orders_amount[{!! $row['id'] !!}]" value="{!! $row['invoicedue'] - $row['paid_amount'] !!}" style="width: 50px" class="amount-charge-val"></td>
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
                        <th colspan="2">Outstanding:
                            ${!! number_format($totals['invoicedue']-$totals['paid_amount'], 2) !!}</th>
                        <th>&nbsp;</th>
                    </tr>

                @else
                    <tr>
                        <td colspan="9">No Records Found!</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>