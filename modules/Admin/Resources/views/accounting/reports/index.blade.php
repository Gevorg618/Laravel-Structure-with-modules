@extends('admin::layouts.master')

@section('title', 'Accounting')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Accounting Reports', 'url' => route('admin.accounting.reports.index')]
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
                    {!! Form::open(['route' => ['admin.accounting.reports.export'],'id' => 'admin_form','class' => 'form-horizontal']) !!}
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
                            {!! Form::text('date_to', Request::get('date_to'),['id' => 'date_to', 'class' => 'datepicker form-control', 'placeholder' => 'Date From']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="clients" class="col-md-2 control-label">Date Type</label>
                        <div class="col-md-10">
                            {!! Form::select('date_type', $dateTypes, Request::get('date_type'), ['id' => 'date_type',
                            'class' => 'form-control',])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="clients" class="col-md-2 control-label">Report</label>
                        <div class="col-md-10">
                            {!! Form::select('report', $reports,  Request::get('report'), [ 'id' => 'report','class' => 'form-control'])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-10">
                            <button type="button" id="accounting_reports_show" value="submit" name="submit" class="btn btn-primary">Search</button>
                            <button type="submit" value="export" name="export" id="export_accounting_reports"
                                    class="btn btn-primary">Download CSV
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive" id="accounting_reports_data"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
