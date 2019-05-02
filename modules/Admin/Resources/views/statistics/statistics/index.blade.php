@extends('admin::layouts.master')

@section('title', 'Statistics & User Tracking')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Statistics & User Tracking', 'url' => '#'],
      ['title' => 'Statistics', 'url' => route('admin.statistics.index')]
    ]
])
@endcomponent

@section('content')
 
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h2><b>Orders Details</b></h2>
                    <div class="panel-body panel-body-table">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        {{ Form::open([ 'route' => 'admin.statistics.show', 'class' => 'form-group', 'id' => 'order-details', 'enctype' => 'multipart/form-data'])}}
                            <input type="hidden" id="request_type" name="request_type" value="placed">
                            @include('admin::statistics.statistics.partials._form', ['button_label' => 'Show'])
                        {{ Form::close() }}                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row hidden" id="nav-content">
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a data-toggle="tab" href="#placed" data-type="placed" >Placed </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#assigned" data-type="assigned" >Assigned </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#low_margin" data-type="low_margin" >Low Margin </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#completed" data-type="completed" >Completed </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#canceled" data-type="canceled" >Canceled </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#charts" data-type="charts" >Charts </a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="placed" class="tab-pane fade in active">
                    @include('admin::statistics.statistics.partials.nav-tabs._placed')
                </div>
                <div id="assigned" class="tab-pane fade">
                    @include('admin::statistics.statistics.partials.nav-tabs._assigned')
                </div>
                <div id="low_margin" class="tab-pane fade">
                    @include('admin::statistics.statistics.partials.nav-tabs._low-margin')
                </div>
                <div id="completed" class="tab-pane fade">
                    @include('admin::statistics.statistics.partials.nav-tabs._completed')
                </div>
                <div id="canceled" class="tab-pane fade">
                    @include('admin::statistics.statistics.partials.nav-tabs._canceled')
                </div>
                <div id="charts" class="tab-pane fade">
                    @include('admin::statistics.statistics.partials.nav-tabs._charts')
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="callendar-orders">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h2><b>Orders Calendar</b></h2>
                    <div class="panel-body panel-body-table">
                        {!! $calendar->calendar() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@push('scripts')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="{{ masset('js/plugins/fullcalendar/fullcalendar.min.js')  }}"></script>
    <script src="{{ masset('js/modules/admin/statistics/statistics/index.js') }}"></script>
    {!! $calendar->script() !!}
@endpush