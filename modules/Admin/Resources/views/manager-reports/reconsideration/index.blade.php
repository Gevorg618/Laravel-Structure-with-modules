@extends('admin::layouts.master')

@section('title', 'Manager Reports')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Reconsideration Report', 'url' => '#']
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
                                    {!! Form::open(['route' => 'admin.reports.reconsideration.download', 'id' => 'form-report-show',  'enctype' => "multipart/form-data"]) !!}
                                    <div class="form-group col-md-6">
                                        <label name="daterange" class="control-label col-lg-3 col-xs-12">Date Range
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {!! Form::text('daterange', null, ['class' => 'form-control daterange']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
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