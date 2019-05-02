@extends('admin::layouts.master')

@section('title', 'Manager Reports')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'User Report Generator', 'url' => '#']
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body ">                        
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    {!! Form::open(['route' => 'admin.reports.user.generator.download', 'enctype' => "multipart/form-data" ]) !!}
                                    <div class="form-group col-md-6">
                                        <label name="daterange" class="control-label col-lg-3 col-xs-12">Date Range
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {!! Form::text('daterange', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label name="status" class="control-label col-lg-3 col-xs-12">Status
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('status', $statuses, null, ['class' => 'form-control' ]) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="datetype" class="control-label col-lg-3 col-xs-12">Date Type
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('datetype', ['placed' => 'Placed', 'accepted' => 'Accepted', 'completed' => 'Completed', 'joined' => 'User Joined'], null, ['class' => 'form-control' ]) }}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label name="company" class="control-label col-lg-3 col-xs-12">Company Name
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {!! Form::text('company', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="min_count" class="control-label col-lg-3 col-xs-12">At Least
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {!! Form::number('min_count', 1, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="address" class="control-label col-lg-3 col-xs-12">Address
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {!! Form::text('address', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="name" class="control-label col-lg-3 col-xs-12">Name / Email
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="city" class="control-label col-lg-3 col-xs-12">City
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {!! Form::text('city', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="user_type" class="control-label col-lg-3 col-xs-12">User Type
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('user_type', $userTypes, null, ['class' => 'form-control' ]) }}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label name="active" class="control-label col-lg-3 col-xs-12"> Active
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('active', ['Y' => 'Yes',  'N' => 'No', '' => '-- ALL --' ], ['Y'], ['class' => 'form-control' ]) }}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label name="user_state" class="control-label col-lg-3 col-xs-12"> State
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('user_state', $states, null, ['class' => 'form-control' ]) }}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label name="is_priority_appr" class="control-label col-lg-3 col-xs-12"> Priority
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('is_priority_appr', ['1' => 'Yes',  '0' => 'No', '' => '-- ALL --' ], null, ['class' => 'form-control' ]) }}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label name="columns" class="control-label col-lg-3 col-xs-12"> State
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('columns[]', $columns, null, ['class' => 'form-control multiselect','multiple' => 'multiple' , 'data-live-search' => "true",
                                             'data-actions-box' => 'true']) }}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label name="exclude" class="control-label col-lg-3 col-xs-12"> Exclude
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('exclude', ['N' => 'No', 'Y' => 'Yes', 'P' => 'Under Review', 'W' => 'Watch', '' => '-- ALL --'], null, ['class' => 'form-control' ]) }}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label name="client" class="control-label col-lg-3 col-xs-12"> User Groups
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('client[]', $clients, null, ['class' => 'form-control multiselect','multiple' => 'multiple' , 'data-live-search' => "true",
                                             'data-actions-box' => 'true']) }}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label name="group_exclude" class="control-label col-lg-3 col-xs-12"> Group Exclude
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('group_exclude', ['Y' => 'Yes', '' => '-- ALL --'], null, ['class' => 'form-control' ]) }}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label name="new_construction_expert" class="control-label col-lg-6 col-xs-12"> New Construction Expert
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('new_construction_expert', ['Y' => 'Yes', '' => '-- ALL --'], null, ['class' => 'form-control' ]) }}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label name="diversity_status" class="control-label col-lg-3 col-xs-12"> Diversity Status
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('diversity_status', ['Y' => 'Yes', '' => '-- ALL --'], null, ['class' => 'form-control' ]) }}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <button type="submit" class="btn btn-success pull-left">Download</button>
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