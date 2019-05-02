@extends('admin::layouts.master')

@section('title', 'Accounting')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Daily Batch', 'url' => route('admin.accounting.daily-batch.index')]
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
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    
                                    {!! Form::open(['route' => ['admin.accounting.daily-batch.export'],'id' => 'daily_batch_form', 'class' => 'form-horizontal']) !!}

                                    <div class="form-group">
                                        <label name="daterange" class="control-label col-lg-3 col-xs-12 required">Date From
                                        </label>
                                        <div class="col-lg-9 col-xs-12">
                                            {!! Form::text('date_from', Request::get('date_from'), ['id' => 'date_from', 'class' => 'datepicker form-control', 'placeholder' => 'Date From'] ) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label name="report_type" class="control-label col-lg-3 col-xs-12 required">Date To
                                        </label>
                                        <div class="col-lg-9 col-xs-12">
                                             {!! Form::text('date_to', Request::get('date_to'), ['id' => 'date_to', 'class' => 'datepicker form-control', 'placeholder' => 'Date To'] ) !!}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label name="date_type" class="control-label col-lg-3 col-xs-12">Type
                                        </label>
                                        <div class="col-lg-9 col-xs-12">
                                            {{ Form::select('type', [null => 'All', 'charges' => 'Charges', 'refunds' => 'Refunds'], null, ['class' => 'form-control', 'id' => 'type']) }}
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top: 20px;">
                                        <div class="col-lg-12 col-xs-12">
                                            <button type="button" id="daily_batch_show" value="submit" name="submit" class="btn btn-primary">
                                                Search
                                            </button>
                                            <button type="submit" value="export" name="export" id="export_al_payable" class="btn btn-success">
                                                Download CSV
                                            </button>
                                        </div>
                                    </div>

                                    {{ Form::close() }}
                                </div>
                            </div>
                         </div>     
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" >
        <div class="col-lg-12 " id="nav-content">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a data-toggle="tabajax" data-url="{{ route('admin.accounting.daily-batch.appr-credit-cards') }}"  href="#appr_credit_cards">Appraisal Credit Cards</a>
                </li>
                <li>
                    <a data-toggle="tabajax" data-url="{{ route('admin.accounting.daily-batch.appr-checks') }}" href="#appr_checks">Appraisal Checks</a>
                </li>
                <li>
                    <a data-toggle="tabajax" data-url="{{ route('admin.accounting.daily-batch.mercury') }}" href="#mercury">Mercury</a>
                </li>
                <li>
                    <a data-toggle="tabajax" data-url="{{ route('admin.accounting.daily-batch.alt-credit-cards') }}" href="#alt_credit_cards">ALT. Credit Cards</a>
                </li>
                <li>
                    <a data-toggle="tabajax" data-url="{{ route('admin.accounting.daily-batch.alt-checks') }}"  href="#alt_checks">ALT. Checks</a>
                </li>
                <li>
                    <a data-toggle="tabajax" data-url="{{ route('admin.accounting.daily-batch.docuvault-checks') }}"  href="#docuvault_checks">DocuVault Checks</a>
                </li>
                <li>
                    <a data-toggle="tabajax" data-url="{{ route('admin.accounting.daily-batch.adjustments') }}" href="#adjustments">Adjustments</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="appr_credit_cards" class="tab-pane fade in active">
                    @include('admin::accounting.daily-batch.partials.nav-tabs.appr-credit-cards')
                </div>
                <div id="appr_checks" class="tab-pane fade">
                    @include('admin::accounting.daily-batch.partials.nav-tabs.appr-checks')
                </div>
                <div id="mercury" class="tab-pane fade">
                    @include('admin::accounting.daily-batch.partials.nav-tabs.mercury')
                </div>
                <div id="alt_credit_cards" class="tab-pane fade">
                    @include('admin::accounting.daily-batch.partials.nav-tabs.alt-credit-cards')
                </div>
                <div id="alt_checks" class="tab-pane fade">
                    @include('admin::accounting.daily-batch.partials.nav-tabs.alt_checks')
                </div>
                <div id="docuvault_checks" class="tab-pane fade">
                    @include('admin::accounting.daily-batch.partials.nav-tabs.docuvault-checks')
                </div>
                <div id="adjustments" class="tab-pane fade">
                    @include('admin::accounting.daily-batch.partials.nav-tabs.adjustments')
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
    <script> 
        $(document).ready(function() {
            $(document).on("click","#daily_batch_show", function() {    
                $('#loading').show();
                console.log("sdasd");
                runActiveTab();
            });
            
            function runActiveTab() {
                console.log("sssssd");
                $("#nav-content").removeClass('hidden');
                $('.nav-tabs > .active').find('a').trigger('click');
            }

            $(document).on("click",'[data-toggle="tabajax"]',function(e) { 
                console.log("cvxcvxcvs");

                
                var loadurl = $(this).data('url');
                var target = $(this).attr('href');
                var dateFrom = $('#date_from').val();
                var dateTo = $('#date_to').val();
                var type = $('#type').val();

                $('#loading').show();
                
                var requestData = {
                    type: 'POST',
                    url: loadurl,
                    data: {
                        date_from: dateFrom,
                        date_to: dateTo,
                        type: type,
                    },
                    error: function (xhr, error, thrown) {
                    }
                };

                switch (target) {

                        case '#appr_credit_cards':

                                $app.datatables('#appr-credit-cards-datatable', requestData, {
                                    columns: [
                                         {data: 'gateway'},
                                         {data: 'ref_type'},
                                         {data: 'created_date'},
                                         {data: 'order_id'},
                                         {data: 'client'},
                                         {data: 'team'},
                                         {data: 'propaddress1'},
                                         {data: 'borrower'},
                                         {data: 'trans_id'},
                                         {data: 'amount'},
                                          
                                    ],
                                    iDisplayLength: 10,
                                    lengthMenu: [ 10, 25, 50, 75, 100 ],
                                    order : false,
                                    retrieve: false,
                                    destroy: true,
                                    searchable: false,
                                    searching: false
                                });
                           break;
                        case '#appr_checks':

                                $app.datatables('#appr-checks-datatable', requestData, {
                                    columns: [
                                         {data: 'ref_type'},
                                         {data: 'created_date'},
                                         {data: 'order_id'},
                                         {data: 'client'},
                                         {data: 'team'},
                                         {data: 'propaddress1'},
                                         {data: 'borrower'},
                                         {data: 'check_number'},
                                         {data: 'date_received'},
                                         {data: 'amount'},
                                    ],
                                    iDisplayLength: 10,
                                    lengthMenu: [ 10, 25, 50, 75, 100 ],
                                    order : false,
                                    destroy: true,
                                    searchable: false,
                                    searching: false
                                });
                           break;
                        case '#mercury':

                                $app.datatables('#mercury-datatable', requestData, {
                                    columns: [
                                         {data: 'gateway'},
                                         {data: 'ref_type'},
                                         {data: 'created_date'},
                                         {data: 'order_id'},
                                         {data: 'client'},
                                         {data: 'team'},
                                         {data: 'propaddress1'},
                                         {data: 'borrower'},
                                         {data: 'trans_id'},
                                         {data: 'amount'},
                                          
                                    ],
                                    iDisplayLength: 10,
                                    lengthMenu: [ 10, 25, 50, 75, 100 ],
                                    order : false,
                                    retrieve: false,
                                    destroy: true,
                                    searchable: false,
                                    searching: false
                                });
                           break;  
                        case '#alt_credit_cards':
                                
                                $app.datatables('#alt-credit-cards-datatable', requestData, {
                                    columns: [
                                         {data: 'gateway'},
                                         {data: 'ref_type'},
                                         {data: 'created_date'},
                                         {data: 'order_id'},
                                         {data: 'client'},
                                         {data: 'team'},
                                         {data: 'propaddress1'},
                                         {data: 'borrower'},
                                         {data: 'trans_id'},
                                         {data: 'amount'},
                                          
                                    ],
                                    iDisplayLength: 10,
                                    lengthMenu: [ 10, 25, 50, 75, 100 ],
                                    order : false,
                                    retrieve: false,
                                    destroy: true,
                                    searchable: false,
                                    searching: false
                                });
                           break;    

                        case '#alt_checks':
                                
                                $app.datatables('#alt-checks-datatable', requestData, {
                                    columns: [
                                        {data: 'ref_type'},
                                        {data: 'created_date'},
                                        {data: 'order_id'},
                                        {data: 'client'},
                                        {data: 'team'},
                                        {data: 'propaddress1'},
                                        {data: 'borrower'},
                                        {data: 'check_number'},
                                        {data: 'date_received'},
                                        {data: 'amount'}
                                    ],
                                    iDisplayLength: 10,
                                    lengthMenu: [ 10, 25, 50, 75, 100 ],
                                    order : false,
                                    retrieve: false,
                                    destroy: true,
                                    searchable: false,
                                    searching: false
                                });
                           break;
                        case '#docuvault_checks':
                                
                                $app.datatables('#docuvault-checks-datatable', requestData, {
                                    columns: [
                                        {data: 'ref_type'},
                                        {data: 'created_date'},
                                        {data: 'order_id'},
                                        {data: 'client'},
                                        {data: 'team'},
                                        {data: 'propaddress1'},
                                        {data: 'borrower'},
                                        {data: 'check_number'},
                                        {data: 'date_received'},
                                        {data: 'amount'}
                                    ],
                                    iDisplayLength: 10,
                                    lengthMenu: [ 10, 25, 50, 75, 100 ],
                                    order : false,
                                    retrieve: false,
                                    destroy: true,
                                    searchable: false,
                                    searching: false
                                });
                           break;
                           case '#adjustments':
                                
                                $app.datatables('#adjustments-datatable', requestData, {
                                    columns: [
                                        {data: 'created_date'},
                                        {data: 'order_id'},
                                        {data: 'client'},
                                        {data: 'team'},
                                        {data: 'propaddress1'},
                                        {data: 'borrower'},
                                        {data: 'reason'},
                                        {data: 'type'},
                                        {data: 'check_number'},
                                        {data: 'amount'}
                                    ],
                                    iDisplayLength: 10,
                                    lengthMenu: [ 10, 25, 50, 75, 100 ],
                                    order : false,
                                    retrieve: false,
                                    destroy: true,
                                    searchable: false,
                                    searching: false
                                });
                           break;   
                       default:
                            $($.fn.dataTable.tables( true ) ).css('width', '100%');
                            $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();   
                            break;
                }
                $('#loading').hide();
                $(this).tab('show');
            });
        });
    </script>
@endpush