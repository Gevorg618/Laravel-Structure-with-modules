@extends('admin::layouts.master')

@section('title', 'Delay Codes')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Customizations', 'url' => '#'],
  ['title' => 'Delay Codes', 'url' => route('admin.appraisal.delay-codes')],
  ['title' => 'New Delay Code', 'url' => route('admin.appraisal.delay-codes.create')]
]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-6">
            {{ Form::model($delayCode, ['route' => [$delayCode->id ? 'admin.appraisal.delay-codes.update' : 'admin.appraisal.delay-codes.create', 'id' => $delayCode->id], 'class' => 'form-horizontal']) }}
            @if($delayCode->id)
                {{ method_field('PUT') }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ ($delayCode->id)?'Updating':'Creating' }}</h3>
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
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('name', 'Name', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('name', old('name', $delayCode->name), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="ibox-footer">
                                    <button class="btn btn-success pull-right">{{ ($delayCode->id)?'Update':'Save' }}</button>
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