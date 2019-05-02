@extends('admin::layouts.master')

@section('title', isset($apiUser) ? "Edit API User" : 'Add API User')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Integrations', 'url' => '#'],
        ['title' => 'API Users', 'url' => route('admin.integrations.api-users')],
        ['title' => isset($apiUser) ? "Edit API User" : 'Add API User', 'url' => isset($apiUser) ? '#' : route('admin.integrations.api-users.create')]
    ],
    'actions' => [
        ['title' => 'API Manager', 'url' => route('admin.integrations.api-users')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="col-md-offset-1 col-md-10">
                            <form method="POST" action="{{!isset($apiUser) ? route('admin.integrations.api-users.store') : route('admin.integrations.api-users.update', ['id' => $apiUser->id])}}">
                                {{ csrf_field() }}
                                {{isset($apiUser) ? method_field('put') : ''}}
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a data-toggle="tab" href="#information">Information</a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#settings">Settings</a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#permissions">Permissions</a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#emails">Emails</a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#subscribers">Subscribers</a>
                                    </li>
                                </ul>
                                @if(!empty($errors->first()))
                                    <div class="col-md-12" style="margin-top: 15px;">
                                        <div class="row col-md-6">
                                            <div class="alert alert-danger alert-dismissible" role="alert">
                                                 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <span>{{ $errors->first() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="tab-content">
                                    <div id="information" class="tab-pane fade in active">
                                        {!! $informationView !!}
                                    </div>
                                    <div id="settings" class="tab-pane fade">
                                        {!! $settingsView !!}
                                    </div>
                                    <div id="permissions" class="tab-pane fade">
                                        {!! $permissionsView !!}
                                    </div>
                                    <div id="emails" class="tab-pane fade">
                                        {!! $emailsView !!}
                                    </div>
                                    <div id="subscribers" class="tab-pane fade">
                                        {!! $subscribersView !!}
                                    </div>
                                    <hr>
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <link href="{{ masset('css/integrations/api_users/create_edit.css') }}" rel="stylesheet" />
    <script type="text/javascript" src="{{ masset('js/integrations/api_users/create_edit.js') }}"></script>
@endpush
