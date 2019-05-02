@extends('admin::layouts.master')

@section('title', 'Accounting')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Accounts Receivable Report', 'url' => route('admin.accounting.receivable-reports.index')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
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
                    <div class="panel-body">
                        <div class="grid_16 alpha">
                            <p>
                                <b>Ignoring Orders:</b> Select the orders you <i><b>Do Not</b></i> want to include in
                                the report (Ignoring them will alter all totals).
                            </p>
                        </div>
                        <div class="clear"></div>
                        @php $cumulativeTotal = 0; @endphp
                        @if($rows && count($rows) && count($rows['rows']))
                            {!! Form::open([
                            'route' => ['admin.accounting.receivable-reports.clients-report'],
                            'id' => 'view_clients_report_form',
                            'class' => 'form-horizontal',
                            'method' => 'GET'
]                           ) !!}
                        {!! Form::hidden('ids', null, ['id' => 'ids']) !!}
                                @php $clients = []; @endphp
                                @foreach($rows['rows'] as $clientId => $data)
                                    @php
                                        $info = $data['data'];
                                        $orders = $data['orders'];
                                        $counts = $data['counts'];
                                    @endphp
                                    <h1>{{ $info->descrip }} ({{ number_format(count($orders)) }})</h1>
                                    <h2 class="total_unchecked"></h2>
                                    <h2 class="total_checked"></h2>

                                    <div class="grid_16 alpha" id='view-clients-data'>
                                        <table cellpadding="0" cellspacing="0" border="0"
                                               class="display order-rows table table-stripe table-condensed"
                                               id="order-rows">
                                            <thead>
                                            <th><input type="checkbox" name="check-all" id="check-all"></th>
                                            <th>ID</th>
                                            <th>Date Ordered</th>
                                            <th>Date Completed</th>
                                            <th>Borrower Name</th>
                                            <th>Product</th>
                                            <th>Address</th>
                                            <th>Amount Due</th>
                                            </thead>
                                            <tbody>
                                            @foreach($orders as $order)
                                                <tr>
                                                    <td style='width:20px;padding:2px;'>
                                                        @php
                                                            $options = ['class' => 'order-checkbox', 'id' => 'ignore_' . $order->id];
                                                            if(in_array($order->appr_type, [71,72,73,74])) {
                                                                $options['checked'] = 'checked';
                                                            }

                                                            if(strtotime($order->ordereddate) <= strtotime('2013-04-30')) {
                                                                $options['checked'] = 'checked';
                                                            }
                                                        @endphp
                                                        <input type="checkbox" name="ignore[]" value="{{ $order->id }}"
                                                               class="order-checkbox" id="ignore-{{ $order->id }}"
                                                               @if(isset($options['checked'])) checked="checked" @endif>
                                                    </td>
                                                    <td><a href="order.php?oid={{ $order->id }}"
                                                           target="_blank">{{ $order->id }}</a></td>
                                                    <td>{{ date('m/d/Y', strtotime($order->ordereddate)) }}</td>
                                                    <td>{{ date('m/d/Y', strtotime($order->completed_date)) }}</td>
                                                    <td>{{ $order->borrower ? $order->borrower : '--' }}</td>
                                                    <td>{{ $order->apprTypeName }}</td>
                                                    <td>{{ $order->address }}</td>
                                                    <td style='text-align:left;'>
                                                        <small>({{ $order->days_group }})</small>
                                                        ${{ number_format($order->amount_due, 2) }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tr>
                                                <th colspan='7'>&nbsp;</th>
                                                <th style='text-align:left;'><span
                                                            style='padding-left:25px;'> ${{ number_format($counts['total']['due'], 2) }}</span>
                                                </th>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="clear"></div>
                                    @php $clients[$info->id] = $info->id;
                            $cumulativeTotal += $counts['total']['due']; @endphp
                                @endforeach

                                @if($cumulativeTotal)

                                    <div class="grid_16 alpha" id='view-clients-data'>
                                        <table cellpadding="0" cellspacing="0" border="0" class="display">
                                            <tr>
                                                <th style='text-align:right;'><span
                                                            style='padding-right:25px;'>Total ${{ number_format($cumulativeTotal, 2) }}</span>
                                                </th>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="clear"></div>
                                @endif

                                <input type='hidden' name='clients' value='{{ implode(',', $clients) }}'/>
                                <input type='hidden' name='filter' value='{{ $filter }}'/>
                                <input type='hidden' name='credits' value='{{ $credits }}'/>
                                <div class="submit" style="width:400px;">
                                    <input type="submit" name="submit" value="Submit" class="btn btn-success"/>
                                    <input type="submit" name="printinvoices" value="Print Invoices"
                                           class="btn btn-success"/>
                                    <input type="submit" name="printlargelabels" value="Print Large Labels"
                                           class="btn btn-success"/>
                                    {!! Form::close() !!}
                                </div>

                            <h2 class="total_unchecked"></h2>
                            <h2 class="total_checked"></h2>
                        @else
                            <h2>No Records Found!</h2>
                        @endif

                        <style>
                            #order-rows td {
                                text-align: center;
                            }
                        </style>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
