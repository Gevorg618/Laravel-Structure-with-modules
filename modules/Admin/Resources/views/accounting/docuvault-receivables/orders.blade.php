@extends('admin::layouts.master')

@section('title', 'Accounting')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'DocuVault Receivables', 'url' => '#']
    ],
])
@endcomponent

@section('content')
    <div class="row">
        @foreach($items as $itemKey => $item)
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h3>{{ $item['title'] }}</h3>
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" class="checked_all_orders"></th>
                                    <th> ID </th>
                                    <th> LOAN # </th>
                                    <th> DATE ORDERED </th>
                                    <th> DATE COMPLETED </th>
                                    <th> NOTIFICATION DATE </th>
                                    <th> BORROWER </th>
                                    <th> PROPERTY ADDRESS </th>
                                    <th> DAYS </th>
                                    <th> BALANCE </th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($item['orders'] as $key => $order)
                                      <tr>
                                        <td><input type="checkbox" name="{{ $itemKey }}" value="{{ $order['id']}}" class="record_id_checkbox"></td>
                                        <td>{{ $order['id'] }}</td>
                                        <td>{{ $order['loanrefnum'] }}</td>
                                        <td>{{ date('m/d/Y', strtotime($order['dateordered'])) }}</td>
                                        <td>{{ date('m/d/Y', strtotime($order['date_delivered'])) }}</td>
                                        <td>{{  date('m/d/Y', $order['notification_date'])  }}</td>
                                        <td>{{ $order['borrower'] }}</td>
                                        <td>{{ $order['address'] . ' ' . $order['city'] . ', ' . $order['state'] }}</td>
                                        <td>{{ $order['dayscategory'] }}</td>
                                        <td>${{ $order['amount'] - $order['paidamount'] }}</td>
                                      </tr>
                                      @endforeach
                                </tbody>
                            </table>
                        </div>                            
                    </div>                     
                </div>
            </div>
        </div>
        @endforeach
    </div>
    {!! Form::open([ 'route' => ['admin.accounting.docuvault-receivables.download'], 'id' => 'data_form', 'multiple' => 'multiple', 'class' => 'form-horizontal']) !!}
    <input type="hidden" name="data" id="chekced_data">
    <div class="row" style="margin-bottom: 100px;">
        <div class="col-md-12">
            <button type="button" class="btn btn-primary download_csv">Download CSV</button>  
            <button type="button" class="btn btn-primary download_stat">Download Statment</button> 
        </div>
    </div>
    {!! Form::close() !!}

    {!! Form::open([ 'route' => ['admin.accounting.docuvault-receivables.statments'], 'id' => 'data_statments']) !!}
        <input type="hidden" name="data" id="chekced_data_stat" >
    {!! Form::close() !!}
@stop
@push('scripts')
    <script src="{{ masset('js/modules/admin/accounting/docuvault-receivables/orders.js') }}"></script>
@endpush