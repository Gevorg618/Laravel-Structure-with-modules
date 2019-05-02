@extends('admin::layouts.master')

@section('title', 'Accounting')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Transactions', 'url' => '#'],
        ['title' => 'Payables Transactions', 'url' => route('admin.accounting.payable.index')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">

                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $payment)
                                        <tr>
                                            <td><a href="{!! route('admin.accounting.payable.show', [$payment->id]) !!}">#{!! $payment->id !!}</a></td>
                                            <td>{!! optional($payment->user)->fullname !!}</td>
                                            <td>{!! date('m/d/Y G:i A', $payment->created_date) !!}</td>
                                        </tr>
                                    @empty
                                        There is no data
                                    @endforelse
                                </tbody>
                            </table>
                            {!! $payments->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
