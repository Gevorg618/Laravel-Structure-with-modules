@extends('admin::layouts.master')

@section('title', 'Edit User Group')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Management', 'url' => '#'],
  ['title' => 'Survey', 'url' => route('admin.management.surveys.index')],
  ['title' => 'Update Survey', 'url' => '']
]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-6">
            {{ Form::model($survey, ['route' => ['admin.management.surveys.update', $survey->id], 'method' => 'patch', 'class' => 'form-horizontal']) }}

            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Update</h3>
                </div>
                @include('admin::management.surveys.partials._form', ['button_label' => 'Update'])
            </div>
        </div>
        {{ Form::close() }}
    </div>
@stop