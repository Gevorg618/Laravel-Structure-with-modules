@extends('admin::layouts.master')

@section('title', 'User Document Types')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Documents & Uploads', 'url' => '#'],
  ['title' => 'User Document Types Manager', 'url' => route('admin.document.user.types')],
  ['title' => 'New User Document Type', 'url' => '']
]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-6">
            {{ Form::model($userDocumentType, ['route' => [$userDocumentType->id ? 'admin.document.user.types.update' : 'admin.document.user.types.create', 'id' => $userDocumentType->id], 'class' => 'form-horizontal']) }}
            @if($userDocumentType->id)
                {{ method_field('PUT') }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ ($userDocumentType->id)?'Updating':'Creating' }}</h3>
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
                                            {{ Form::text('title', old('title', $userDocumentType->title), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="ibox-footer">
                                    <button class="btn btn-success pull-right">{{ ($userDocumentType->id)?'Update':'Save' }}</button>
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