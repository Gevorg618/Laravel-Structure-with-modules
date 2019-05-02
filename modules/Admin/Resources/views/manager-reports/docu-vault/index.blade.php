@extends('admin::layouts.master')

@section('title', 'DocuVault Report Generator')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'DocuVault Report Manager', 'url' => '#']
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body">                        
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    {!! Form::open(['route' => 'admin.reports.docu.vault.download', 'id' => 'form-report-show',  'enctype' => "multipart/form-data"]) !!}
                                    <div class="form-group col-md-6">
                                        <label name="daterange" class="control-label col-lg-3 col-xs-12">Date Range
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {!! Form::text('daterange', null, ['class' => 'form-control daterange']) !!}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="client" class="control-label col-lg-3 col-xs-12"> User Groups
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('client[]', $clients, null, ['class' => 'form-control multiselect client','multiple' => 'multiple' , 'data-live-search' => "true",
                                            ]) }}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label name="date_type" class="control-label col-lg-3 col-xs-12"> Date Type
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('date_type', ['ordereddate' => 'Ordered Date',  'notification_date' => 'Notification Date', 'delivered_date' => 'Report Delivered Date', 
                                                    'borrower_confirmation_date' => 'Borrower Confirmation Date', ], null, ['class' => 'form-control']) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="lenders" class="control-label col-lg-3 col-xs-12"> Lenders
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('lenders[]', $lenders, null, ['class' => 'form-control multiselect lenders','multiple' => 'multiple' , 'data-live-search' => "true",
                                             ]) }}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <div class="col-lg-12 col-xs-12">
                                            <button type="submit" class="btn btn-primary generate-report download-report">Download</button>
                                        </div>
                                    </div>    
                                    {!! Form::close() !!}
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
    <script type="text/javascript">
        $(function() {
            $('input[name="daterange"]').daterangepicker();
            $('.multiselect').selectpicker();
        });
    </script>
@endpush