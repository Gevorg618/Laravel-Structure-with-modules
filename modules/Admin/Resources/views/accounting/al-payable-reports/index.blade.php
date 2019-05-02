@extends('admin::layouts.master')

@section('title', 'Accounting')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'AL Accounts Payable', 'url' => route('admin.accounting.export-check.index')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {!! Form::open([
                    'route' => ['admin.accounting.al-payable-reports.index'],
                    'id' => 'admin_form',
                    'class' => 'form-horizontal',
                    'method' => 'GET'
                ]) !!}
                    <div class="form-group">
                        <label for="date_from" class="col-md-2 control-label">Date From</label>

                        <div class="col-md-2">
                            {!! Form::text('date_from', Request::get('date_from'),
                                ['id' => 'date_from', 'class' => 'datepicker form-control', 'placeholder' => 'Date From']
                            ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date_to" class="col-md-2 control-label">Date To</label>
                        <div class="col-md-2">
                            {!! Form::text('date_to', Request::get('date_to'),
                                ['id' => 'date_to', 'class' => 'datepicker form-control', 'placeholder' => 'Date From']
                            ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="clients" class="col-md-2 control-label">States</label>
                        <div class="col-md-10">
                            {!! Form::select('states[]', $states,
                        Request::get('states'),
                        [
                            'id' => 'states',
                            'class' => 'form-control bootstrap-multiselect',
                            'multiple' => 'multiple'
                        ])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="clients" class="col-md-2 control-label">Clients</label>
                        <div class="col-md-10">
                            {!! Form::select('clients[]', $clients,
                        Request::get('clients'),
                        [
                            'id' => 'clients',
                            'class' => 'form-control bootstrap-multiselect',
                            'multiple' => 'multiple'
                        ])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-10">
                            <button type="submit" value="submit" name="submit" class="btn btn-primary">Search</button>
                            <button type="submit" value="export" name="export" id="export_al_payable" class="btn btn-primary">Download CSV</button>
                        </div>
                    </div>
                    {!! Form::close() !!}

                    <h1>Found <span class='number_total'></span> Results</h1>
                    @if(isset($rows) && count($rows))
                        @foreach($rows as $row)
                            <div class="panel-body panel-body-table">
                                <h3>{!! $row['firstname'].' '. $row['lastname'] !!}</h3>
                                <p>{!! $row['company'] !!}</p>
                                <small>Column count  <b> ({!! count($row['orders']) !!}) </b></small>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Date Placed</th>
                                            <th>Date Completed</th>
                                            <th>Order ID</th>
                                            <th>Address</th>
                                            <th>Client</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            <th>Client Balance</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @if(isset($row['orders']) && count($row['orders']))
                                            @foreach($row['orders'] as $order)
                                                <tr>
                                                    <td class='order-row'>{!! date('m/d/Y', strtotime($order->ordereddate)) !!}</td>
                                                    <td>{!! $order->completed_date_human !!}</td>
                                                    <td><a href="" target="_blank">{!! $order->id !!}</a></td>
                                                    <td>{!! $order->address !!}</td>
                                                    <td>{!! optional($order->userData)->company !!}</td>
                                                    <td>{!! $statuses[$order->status] ?? 'N/A' !!}</td>
                                                    <td>${!! number_format($order->split_amount) !!}</td>
                                                    <td>
                                                        ${!! number_format($order->invoicedue - $order->paid_amount) !!}</td>
                                                </tr>
                                            @endforeach
                                            <tr style="background-color: #cce8ff">
                                                <th colspan='6'>&nbsp;</th>
                                                <th>${!! number_format($row['sum_total']) !!}</th>
                                                <th>${!! number_format($row['balance_total']) !!}</th>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="8">There are no records to display.</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                        <div class="panel-body panel-body-table" style="padding-top: 0px; ">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <tr style="background-color: #cce8ff">
                                        
                                        <th>
                                            <div class="total_sum">${!! number_format($totalSum) !!}</div>
                                        </th>
                                        <th style="width: 13%;">
                                            <div class="total_sum">${!! number_format($totalBalance) !!}</div>
                                        </th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="clear"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <script src="{{ masset('js/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/use-multiselect.js') }}"></script>
@endpush