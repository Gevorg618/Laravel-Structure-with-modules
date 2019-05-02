@extends('admin::layouts.master')

@section('title', 'Zip Code')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Management', 'url' => '#'],
  ['title' => 'Zip Code', 'url' => route('admin.management.zipcodes')],
  ['title' => 'New Zip Code', 'url' => '']
]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-6">
            {{ Form::model($ZipCode, ['route' => [$ZipCode->zip_code ? 'admin.management.zipcodes.update' : 'admin.management.zipcodes.create', 'zip_code' => $ZipCode->zip_code], 'class' => 'form-horizontal']) }}
            @if($ZipCode->zip_code)
                {{ method_field('PUT') }}
                {{ Form::hidden('update_id',$ZipCode->zip_code) }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ ($ZipCode->zip_code)?'Updating':'Creating' }}</h3>
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
                                            {{ Form::label('zip_code', 'Zip Code', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('zip_code', old('zip_code', $ZipCode->zip_code), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('city', 'City', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('city', old('city', $ZipCode->city), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('county', 'County', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('county', old('county', $ZipCode->county), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('state', 'State', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('state',$states,old('state',$ZipCode->state), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('long', 'Long', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('long',old('long',$ZipCode->long), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('lat', 'lat', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('lat',old('lat',$ZipCode->lat), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="ibox-footer">
                                    <button class="btn btn-success pull-right">{{ ($ZipCode->zip_code)?'Update':'Save' }}</button>
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