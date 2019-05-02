@extends('admin::layouts.master')

@section('title', 'AMC Registrations')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Customizations', 'url' => '#'],
  ['title' => 'AMC Registrations', 'url' => route('admin.management.amc-licenses')],
  ['title' => 'New AMC Registration', 'url' => '']
]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-6">
            {{ Form::model($AMCLicense, ['route' => [$AMCLicense->id ? 'admin.management.amc-licenses.update' : 'admin.management.amc-licenses.create', 'id' => $AMCLicense->id], 'class' => 'form-horizontal']) }}
            @if($AMCLicense->id)
                {{ method_field('PUT') }}
                {{ Form::hidden('update_id',$AMCLicense->id) }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ ($AMCLicense->id)?'Updating':'Creating' }}</h3>
                </div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('reg_number', 'Registration Number', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('reg_number', old('reg_number', $AMCLicense->reg_number), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('expires', 'License Expiration', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('expires', old('expires', $AMCLicense->expires), ['class' => 'form-control datepicker']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('sec_expires', 'Secretary Of State Expiration', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('sec_expires', old('sec_expires', $AMCLicense->sec_expires), ['class' => 'form-control datepicker']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('state', 'State', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('state',$states,old('state',$AMCLicense->state), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="ibox-footer">
                                    <button class="btn btn-success pull-right">{{ ($AMCLicense->id)?'Update':'Save' }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@stop
@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('.datepicker').datetimepicker({
            format:'YYYY-MM-DD'
        });
    });
</script>
@endpush