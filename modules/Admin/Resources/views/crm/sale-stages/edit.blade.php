@extends('admin::layouts.master')

@section('title', 'Sale Stages')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'CRM', 'url' => '#'],
      ['title' => 'Sale Stages', 'url' => route('admin.crm.sale.stages.index')],
      ['title' => 'Edit Sale Stage', 'url' => '#']
    ]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-9">
            {{ Form::model($saleStage, ['route' => [$saleStage->id ? 'admin.crm.sale.stages.update' : 'admin.crm.sale.stages.create', 'id' => $saleStage->id], 'class' => 'form-horizontal']) }}
            @if($saleStage->id)
                {{ method_field('PUT') }}
                {{ Form::hidden('update_id', $saleStage->id) }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ ($saleStage->id)?'Updating':'Creating' }}</h3>
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
                                            {{ Form::label('title', 'Title', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('title', old('title', $saleStage->title), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('visible', 'Visible', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('visible',[0 => 'No',1 => 'Yes'],old('visible', $saleStage->visible), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="ibox-footer">
                                    <button class="btn btn-success pull-right">{{ ($saleStage->id)?'Update':'Save' }}</button>
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