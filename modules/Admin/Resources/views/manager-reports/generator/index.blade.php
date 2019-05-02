@extends('admin::layouts.master')

@section('title', 'DocuVault Report Generator')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Generator Report', 'url' => '#']
    ],
    'actions' => [
      ['title' => 'Tasks', 'url' => "#"],
    ]
])
@endcomponent

@section('content')

    
    <div class="row first_content">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    {!! Form::open(['route' => 'admin.reports.generator.download', 'id' => 'form-report-show',  'enctype' => "multipart/form-data"]) !!}
                                    <div class="form-group col-md-6">
                                        <label name="daterange" class="control-label col-lg-3 col-xs-12"> Date Range
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {!! Form::text('daterange', null, ['class' => 'form-control daterange']) !!}
                                        </div>                                        
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="client" class="control-label col-lg-3 col-xs-12"> Date type
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('datetype', $dateTypes, old('datetype'), ['class' => 'form-control']) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="client" class="control-label col-lg-3 col-xs-12"> User Groups
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('client[]', $clients, old('client'), ['class' => 'form-control multiselect client', 'id' => 'client' ,'multiple' => 'multiple' , 'data-live-search' => "true",
                                            ]) }}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label name="lenders" class="control-label col-lg-3 col-xs-12"> Wholesale Lenders
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('lenders[]', $lenders, old('lenders'), ['class' => 'form-control multiselect lenders','multiple' => 'multiple' , 'data-live-search' => "true",
                                             ]) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="columns" class="control-label col-lg-3 col-xs-12"> Columns
                                            <span class="required" aria-required="true"></span>
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('columns[]', $columns,  old('columns'), ['class' => 'form-control multiselect ',  'id' => 'columns', 'multiple' => 'multiple' , 'data-live-search' => "true",
                                             ]) }}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label name="status" class="control-label col-lg-3 col-xs-12"> Status
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('status', $statuses, old('team'), ['class' => 'form-control', 'id' => 'status']) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="team" class="control-label col-lg-3 col-xs-12"> Team
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('team', [null => '--'] + $teams->all(), old('team'), ['class' => 'form-control', 'id' => 'team']) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="client" class="control-label col-lg-3 col-xs-12"> Client Approval 
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('is_client_approval', ['Y' => 'Yes', 'N' => 'No', '' => '-- ALL --'], old('is_client_approval'), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label name="client" class="control-label col-lg-3 col-xs-12"> Appraiser
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            <input type="hidden" name="appr_id" id="appr_id">
                                            {{ Form::text('appraiser_name',  old('appraiser_name'), ['class' => 'form-control typeahead']) }}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label name="apprTypes" class="control-label col-lg-3 col-xs-12"> Appraisal Types
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('type[]', $apprTypes, old('type'), ['class' => 'form-control multiselect ', 'id' => 'appr_types', 'multiple' => 'multiple' , 'data-live-search' => "true",
                                             ]) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="states" class="control-label col-lg-3 col-xs-12"> States
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('states[]', $states, old('states'), ['class' => 'form-control multiselect ', 'id' => 'states', 'multiple' => 'multiple' , 'data-live-search' => "true",
                                             ]) }}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <div class="col-lg-6 col-xs-12">
                                            <button type="button" class="btn btn-primary generate-report download-report">Download</button>
                                            <button type="button" class="btn btn-warning" id="save_as_task">Save As Task</button>
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

    <div class="row second_content hidden" >
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body">                        
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-12 second_content_data">

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    <script src="{{ masset('js/modules/admin/report-manager/generator/main.js') }}"></script>
    <script type="text/javascript">

        $('input.typeahead').typeahead({
            source:  function (query, process) {
                return $.get('{!! route('admin.reports.generator.search') !!}', { query: query }, function (data) {
                
                    objects = [];
                    map = {};
                
                    $.each(data, function(i, object) {
                        map[object.label] = object;
                        objects.push(object.label);
                    });
                
                    process(objects);
                
                });
            },
            updater: function(item) {
                $('#appr_id').val(map[item].value);
                return item;
            }
        });

        $(function() {
            $('input[name="daterange"]').daterangepicker();
            $('.multiselect').selectpicker();
        });
        
    </script>
@endpush