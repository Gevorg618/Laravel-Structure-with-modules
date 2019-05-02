@extends('admin::layouts.master')

@section('title', 'Active Users')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Admin User', 'url' => '#'],
        ['title' => 'Active Users', 'url' => route('admin.management.active-users')]
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
                            <h2>{{$totalActiveSessions - $totalActiveGuestSessions}} Active user(s) in the past {{$time}} minutes. {{$totalActiveGuestSessions}} Guest(s).</h2>
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#web">Web</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#app">App</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="web" class="tab-pane fade in active">
                                    {!! $webView !!}
                                </div>
                                <div id="app" class="tab-pane fade">
                                    {!! $appView !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('style')
    <link href="{{ masset('css/management/active_users/index.css') }}" rel="stylesheet" type="text/css">
@endpush
