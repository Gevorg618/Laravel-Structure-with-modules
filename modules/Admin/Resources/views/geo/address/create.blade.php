@extends('admin::layouts.master')

@section('title', 'Address Geo Code')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Geo', 'url' => '#'],
  ['title' => 'Address Geo Code', 'url' => route('admin.geo.address')],
  ['title' => 'New Geo Code', 'url' => route('admin.geo.address.create')]
]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-8">
            {{ Form::model($addressGeoCode, ['route' => [$addressGeoCode->id ? 'admin.geo.address.update' : 'admin.geo.address.create', 'id' => $addressGeoCode->id], 'class' => 'form-horizontal']) }}
            @if($addressGeoCode->id)
                {{ method_field('PUT') }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ ($addressGeoCode->id)?'Updating':'Creating' }}</h3>
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
                                    <div class="form-group">
                                        {{ Form::label('address', 'Add Address or Zip Code', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('address', old('address', $addressGeoCode->address), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="ibox-footer">
                                    <button class="btn btn-success pull-right">{{ ($addressGeoCode->id)?'Update':'Save' }}</button>
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
    $(document).ready(function () {
        $('.datepicker').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });
</script>
@endpush