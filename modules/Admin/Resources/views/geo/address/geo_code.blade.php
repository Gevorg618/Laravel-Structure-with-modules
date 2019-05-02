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
        {{ Form::open(['route' => 'admin.geo.address.create_geo_code', 'class' => 'form-horizontal']) }}
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">Creating</h3>
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
                                        {{ Form::text('address', old('address'), ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="ibox-footer">
                                <button class="btn btn-success pull-right">Geo Code</button>
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