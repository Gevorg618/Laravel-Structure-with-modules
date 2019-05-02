@extends('admin::layouts.master')

@section('title', 'Appraisal - ' . ($row->exists ? 'Updating Access type' : 'Creating Access Type'))

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Appraisal', 'url' => '#'],
  ['title' => 'Access Type', 'url' => route('admin.appraisal.access_type.index')],
  ['title' => ($row->exists ? 'Updating Access type' : 'Creating Access Type'), 'url' => '']
]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            {{ Form::model($row, ['route' => [$row->exists ? 'admin.appraisal.access_type.update' : 'admin.appraisal.access_type.store', 'id' => $row->id], 'class' => 'form-horizontal']) }}
            {{ Form::hidden('id', $row->exists ? $row->id : 0) }}

            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $row->exists ? 'Updating ' . $row->title : 'Creating Access Type' }}</h3>
                </div>
                <div class="panel-body">
                    @if ($errors->all())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('name', 'Name', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-6 col-xs-12">
                                            {{ Form::text('name', old('name', $row->name), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('ord', 'Is Active?', ['class' => 'col-lg-3 col-xs-12']) }}
                                        <div class="col-lg-6 col-xs-12">
                                            {{ Form::select('is_active', [0 => 'Not active', 1 => 'Active'], old('is_active', $row->is_active), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="ibox-footer">
                                    <button class="btn btn-success pull-right">Save</button>
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