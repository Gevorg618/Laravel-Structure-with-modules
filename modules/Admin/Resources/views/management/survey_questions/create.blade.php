@extends('admin::layouts.master')

@section('title', 'New Survey Question')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Management', 'url' => '#'],
  ['title' => 'Surveys', 'url' => route('admin.management.surveys.index')],
  ['title' => 'New Survey Question', 'url' => '']
]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-6">
            {{ Form::open(['route' => 'admin.management.surveys.questions.store', 'method' => 'post', 'class' => 'form-horizontal']) }}

            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Create</h3>
                </div>
                @include('admin::management.survey_questions.partials._form', ['button_label' => 'Submit'])
            </div>
            {{ Form::close() }}
        </div>
    </div>
@stop