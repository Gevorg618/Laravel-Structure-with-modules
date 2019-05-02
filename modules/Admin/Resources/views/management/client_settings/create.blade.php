@extends('admin::layouts.master')

@section('title', 'Adding New Group')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Adding New Group', 'url' => '']
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            {{ Form::open(['route' => 'admin.management.client.store', 'method' => 'post', 'class' => 'form-horizontal']) }}
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Create</h3>
                </div>
                @include('admin::management.client_settings.partials._form', ['button_label' => 'Submit'])
                <div class="form-group" style="padding: 40px">
                    <button class="btn btn-success pull-right" >Save</button>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@stop

@push('style')
    <style>
        .text-info {
            color: #3a87ad;
            font-size: 17.5px
        }
    </style>
@endpush
