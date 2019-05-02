@extends('admin::layouts.master')

@section('title', 'Payables Transaction')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Payable Transactions', 'url' => route('admin.accounting.payable.index')],
        ['title' => 'Current Transaction', 'url' => '#']
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                <div class="panel-body">
                    <h3>#{!! $payment->id !!} Created By {!! $payment->user->fullname !!} On {!! date('m/d/Y g:i A', $payment->created_date) !!}</h3>
                </div>
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th>Appraiser</th>
                                    <th>Address</th>
                                    <th>Check #</th>
                                    <th>Amount</th>
                                    <th>Pay Date</th>
                                    <th>Order</th>
                                    <th>Delivered</th>
                                    <th>Split</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($records as $record)
                                    <tr>
                                        <td><a href='/admin/user.php?action=view-user&id={!! $record->uid !!}' target='_blank'>{!! $record->name !!}</a></td>
                                        <td>{!! $record->address !!}, {!! $record->city !!}, {!! $record->state !!} {!! $record->zip !!}</td>
                                        <td>{!!  $record->check_number !!}</td>
                                        <td>{!! \Modules\Admin\Helpers\StringHelper::formatValue($record->check_amount, 'currency') !!}</td>
                                        <td>{!! $record->pay_date !!}</td>
                                        <td><a href='/admin/order.php?id={!! $record->orderid !!}' target='_blank'>{!! $record->orderid !!}</a></td>
                                        <td>{!! $record->date_delivered !!}</td>
                                        <td>{!! \Modules\Admin\Helpers\StringHelper::formatValue($record->split, 'currency') !!}</td>
                                    </tr>
                                @empty
                                    There is no data
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
