@extends('admin::layouts.master')

@section('title', 'New User Group')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Management', 'url' => '#'],
      ['title' => 'User Groups', 'url' => route('admin.management.groups.index')],
      ['title' => 'New User Group', 'url' => '']
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-6">
            {{ Form::open(['route' => 'admin.management.groups.store', 'method' => 'post', 'class' => 'form-horizontal']) }}

            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Create</h3>
                </div>
                @include('admin::management.groups.partials._form', ['button_label' => 'Submit'])
            </div>
        </div>
        {{ Form::close() }}
    </div>
@stop