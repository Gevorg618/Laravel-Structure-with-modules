@extends('admin::layouts.master')

@section('title', 'Resource Documents')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Documents & Uploads', 'url' => '#'],
      ['title' => 'Resource Document', 'url' => route('admin.document.resource')],
      ['title' => 'New Resource Documents', 'url' => '']
    ]
])
@endcomponent


@section('content')
    <div class="row">
        <div class="col-lg-6">
            {{ Form::model($resource, ['route' => [$resource->id ? 'admin.document.resource.update' : 'admin.document.resource.create', 'id' => $resource->id], 'class' => 'form-horizontal']) }}
            @if($resource->id)
                {{ method_field('PUT') }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ ($resource->id)?'Updating':'Creating' }}</h3>
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
                                        {{ Form::label('title', 'Title', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('title', old('title', $resource->title), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('link', 'Link', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('link', old('link', $resource->link), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('description', 'Description', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::textarea('description', old('description', $resource->description), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('type', 'Type', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('type',['' => '-','docuvault' => 'Docuvault'],old('type',$resource->type), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="ibox-footer">
                                    <button class="btn btn-success pull-right">{{ ($resource->id)?'Update':'Save' }}</button>
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