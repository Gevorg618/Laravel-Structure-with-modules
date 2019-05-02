@extends('admin::layouts.master')

@section('title', 'Settings Manager - ' . ($row->exists ? 'Updating Category' : 'Creating Category'))

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Tools', 'url' => '#'],
  ['title' => 'Settings Manager', 'url' => route('admin.tools.settings')],
  ['title' => ($row->exists ? 'Updating Category' : 'Creating Category'), 'url' => '']
]
])
@endcomponent

@section('content')
<div class="row">
  <div class="col-lg-12">
    {{ Form::model($row, ['route' => [$row->exists ? 'admin.tools.settings.category.update' : 'admin.tools.settings.category.create', 'id' => $row->id], 'class' => 'form-horizontal']) }}
    {{ Form::hidden('id', $row->exists ? $row->id : 0) }}
    
    <div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">{{ $row->exists ? 'Updating ' . $row->title : 'Creating Category' }}</h3>
      </div>
      <div class="panel-body">
        @if (!$errors->isEmpty())
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
                  {{ Form::label('title', 'Title', ['class' => 'col-lg-3 col-xs-12 required']) }}
                  <div class="col-lg-6 col-xs-12">
                    {{ Form::text('title', old('title', $row->title), ['class' => 'form-control']) }}                                                   
                  </div>
                </div>
                <div class="form-group">
                  {{ Form::label('ord', 'Position', ['class' => 'col-lg-3 col-xs-12']) }}
                  <div class="col-lg-6 col-xs-12">
                    {{ Form::text('ord', old('ord', $row->ord), ['class' => 'form-control']) }}                                                   
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  {{ Form::label('description', 'Description', ['class' => 'col-lg-3 col-xs-12']) }}
                  <div class="col-lg-6 col-xs-12">
                    {{ Form::textarea('description', old('description', $row->description), ['class' => 'form-control', 'rows' => 4]) }}                                                   
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