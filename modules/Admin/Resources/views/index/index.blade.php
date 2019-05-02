@extends('admin::layouts.master')

@section('content')
    <div class="row">
        <div class="box-header">
            <ul class="nav nav-tabs" id="hometabs">
                <li class="active">
                    <a href="#dashboard" data-toggle="tab" data-toggle-name="dashboard">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="#announcements" data-toggle="tab" data-toggle-name="announcements">
                        Announcements
                    </a>
                </li>
                <li>
                    <a href="#calendar" data-toggle="tab" data-toggle-name="calendar">
                        Calendar
                    </a>
                </li>
            </ul>
            <div class="tab-content" style="margin-top: 25px">
                <div id="dashboard" class="tab-pane fade in active">
                    @include('admin::index.partials._dashboard', [ 'dashboardData' => $dashboardData ])
                </div>
                <div id="announcements" class="tab-pane fade">
                    @include('admin::index.partials._announcements', [ 'announcementsData' => $announcementsData ])
                </div>
                <div id="calendar" class="tab-pane fade ">
                    @include('admin::index.partials._calendar', [ 'calendarData' => $calendarData ])
                </div>
            </div>
        </div>
    </div>
@endsection



