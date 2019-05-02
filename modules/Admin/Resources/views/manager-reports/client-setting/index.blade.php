@extends('admin::layouts.master')

@section('title', 'Manager Reports')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Client Setting Reports', 'url' => '#']
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
                                    {!! Form::open(['route' => 'admin.reports.client.setting.download', 'id' => 'form-report-show',  'enctype' => "multipart/form-data"]) !!}
                                    <div class="form-group col-md-6">
                                        <label name="daterange" class="control-label col-lg-3 col-xs-12">Date Range
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {!! Form::text('daterange', null, ['class' => 'form-control daterange']) !!}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="report_type" class="control-label col-lg-3 col-xs-12">Report
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('report_type', $reportList, null, ['class' => 'form-control report_type' ]) }}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label name="date_type" class="control-label col-lg-3 col-xs-12">Report
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('date_type', ['created_date' => 'Created Date'], null, ['class' => 'form-control date_type' ]) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="client" class="control-label col-lg-3 col-xs-12"> User Groups
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('client[]', $clients, null, ['class' => 'form-control multiselect client','multiple' => 'multiple' , 'data-live-search' => "true",
                                             'data-actions-box' => 'true']) }}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div class="col-lg-12 col-xs-12">
                                            <button type="button" class="btn btn-warning generate-report show-report">Show</button>
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

    <div class="row report-list-show hidden">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="report-result-datatable">
                                <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Team</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
                                    <th>Note</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <script src="{{ masset('js/modules/admin/report-manager/client-settings/index.js') }}"></script>
@endpush