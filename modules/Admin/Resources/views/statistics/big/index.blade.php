@extends('admin::layouts.master')

@section('title', 'Statistics & User Tracking')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Statistics & User Tracking', 'url' => '#'],
      ['title' => 'Big Statistics', 'url' => route('admin.statistics.big.index')]
    ]
])
@endcomponent

@section('content')
    
    <input type="hidden" id="query_date" value="{{$date}}">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h1><b id='current_time'></b></h1>(Refreshing In <span id="counter_number"></span>)
                    <div class="panel-body panel-body-table">
                        <div class="span12">
                            <div id="orders-placed" style="width:350px;height:200px;float:left;"></div>
                            <div id="orders-assigned" style="width:350px;height:200px;float:left;"></div>
                            <div id="orders-completed" style="width:350px;height:200px;float:left;"></div>
                            <div id="orders-canceled" style="width:350px;height:200px;float:left;"></div>
                        </div>            
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h1><b id='current_time'></b></h1>
                    <div class="panel-body panel-body-table">
                        <div class="row-fluid">
                            <div class="col-md-6">
                              <div id="all-orders" style="width:100%;height:400px;"></div>
                            </div>
                            <div class="col-md-6">
                              <div id="team-orders" style="width:100%;height:400px;"></div>
                            </div>
                        </div>           
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@push('scripts')
   <script src="{{ masset('js/plugins/morris/morris.js') }}"></script>
   <script src="{{ masset('js/plugins/morris/raphael-2.1.0.min.js') }}"></script>
   <script src="{{ masset('js/modules/admin/statistics/big/index.js') }}"></script>
@endpush