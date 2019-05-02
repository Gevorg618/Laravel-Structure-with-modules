@extends('admin::layouts.master')


@section('title', isset($category) ? "Edit category" : 'Create category')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Support tickets', 'url' => '#'],
        ['title' => isset($category) ? "Edit category" : 'Create category', 'url' => isset($category) ? route('admin.ticket.categories.edit', ['id' => $category->id]) : route('admin.ticket.categories.create')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-6">
            @if(isset($category))
                {{ Form::model( $category, ['route' => ['admin.ticket.categories.update', 'id' => $category->id], 'class' => 'form-horizontal']) }}
                {{method_field('put')}}
            @else
                {{ Form::open(['route' => ['admin.ticket.categories.store'], 'class' => 'form-horizontal']) }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{isset($category) ? 'Updating' : 'Creating'}}</h3>
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
                                            {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('user_type_id', 'Visible', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('user_type_id', $user_types, old('user_type_id',isset($category) && !$category->userTypes->isEmpty() ? $category->userTypes[0]->id : null), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('order_status_id', 'Apraisal statuses', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('order_status_id', $statuses, old('order_status_id', isset($category) && !$category->orderStatuses->isEmpty() ? $category->orderStatuses[0]->id : null), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('bgcolor', 'Background color', ['class' => 'col-lg-3 col-xs-12']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('bgcolor', old('bgcolor'), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('textcolor', 'Text color', ['class' => 'col-lg-3 col-xs-12']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('textcolor', old('textcolor'), ['class' => 'form-control']) }}
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