@extends('admin::layouts.master')

@section('title', 'A/P Calendar')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'A/P Calendar', 'url' => route('admin.ap_calendar.index')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left"><h1>A/P Calendar</h1></div>
                                <div class="pull-right">
                                    <span class="label label-success">15 Days</span>
                                    <span class="label label-info">30 Days</span>
                                    <span class="label label-warning">45 Days</span>
                                    <span class="label label-danger">60+ Days</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div id='ap_calendar'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <link rel="stylesheet" href="{!! masset('css/plugins/fullcalendar/fullcalendar.css') !!}"/>
    <script src="{!! masset('js/plugins/fullcalendar/fullcalendar.min.js') !!}"></script>
    <script src="{!! masset('js/main.js') !!}"></script>
@endpush