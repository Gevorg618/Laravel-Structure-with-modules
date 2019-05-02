@extends('admin::layouts.master')

@section('title', 'Statistics & User Tracking')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Statistics & User Tracking', 'url' => '#'],
      ['title' => 'Dashboard Statistics', 'url' => route('admin.statistics.index')]
    ]
])
@endcomponent

@section('content')
 
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h2><b>Dashboard Statistics</b></h2>
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
                        {{ Form::open([ 'route' => 'admin.statistics.dashboard.show', 'class' => 'form-group', 'id' => 'order-details', 'enctype' => 'multipart/form-data'])}}
                            <input type="hidden" id="request_type" name="request_type" value="team">
                            @include('admin::statistics.dashboard.partials._form', ['button_label' => 'Show'])
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
                    <a data-toggle="tab" href="#team" data-type="team" >Team </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#users" data-type="users" >Users </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#transferred_orders" data-type="transferred_orders" >Transferred Orders</a>
                </li>
                <li>
                    <a data-toggle="tab" href="#daily_stats" data-type="daily_stats" >Daily Stats </a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="team" class="tab-pane fade in active">
                    @include('admin::statistics.dashboard.partials.nav-tabs._team')
                </div>
                <div id="users" class="tab-pane fade">
                    @include('admin::statistics.dashboard.partials.nav-tabs._users')
                </div>
                <div id="transferred_orders" class="tab-pane fade">
                    @include('admin::statistics.dashboard.partials.nav-tabs._transferred_orders')
                </div>
                <div id="daily_stats" class="tab-pane fade">
                    @include('admin::statistics.dashboard.partials.nav-tabs._daily_stats')
                </div>
            </div>
        </div>
    </div>

@stop

@push('scripts')
    <script src="{{ masset('js/modules/admin/statistics/dashboard/index.js') }}"></script>
@endpush