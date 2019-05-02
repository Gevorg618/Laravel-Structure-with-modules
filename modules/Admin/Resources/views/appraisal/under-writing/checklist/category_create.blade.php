@extends('admin::layouts.master')

@section('title', 'Appraisal UW Checklist')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Appraisal', 'url' => '#'],
  ['title' => 'Appraisal UW Checklist', 'url' => route('admin.appraisal.under-writing.checklist')],
  ['title' => 'New UW Category', 'url' => route('admin.appraisal.under-writing.checklist')]
],
'actions' => [
  ['title' => 'Add Question', 'url' => route('admin.appraisal.under-writing.checklist.question.create')],
]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-8">
            {{ Form::model($category, ['route' => [$category->id ? 'admin.appraisal.under-writing.checklist.category.update' : 'admin.appraisal.under-writing.checklist.category.create', 'id' => $category->id], 'class' => 'form-horizontal']) }}
            @if($category->id)
                {{ method_field('PUT') }}
                {{ Form::hidden('update_id',$category->id) }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ ($category->id)?'Updating':'Creating' }}</h3>
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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {{ Form::label('title', 'Title', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('title', old('title', $category->title), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('is_active', 'Active', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('is_active',['1' => 'Yes','0' => 'No'],$category->is_active, ['class' => 'form-control','data-live-search' => 'true']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-success pull-right">{{ ($category->id)?'Update':'Save' }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@stop

@push('scripts')

@endpush