@extends('admin::layouts.master')

@section('title', 'Statistics & User Tracking')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Statistics & User Tracking', 'url' => '#'],
      ['title' => 'Accounting Big Statistics', 'url' => route('admin.statistics.big.index')]
    ]
])
@endcomponent

@section('content')
    <style>
    
</style>
@push('style')
    <link rel="stylesheet" href="{{ masset('css/statistics/accounting-big/main.css') }}">
@endpush
    <div class="row">
        <div class="col-xs-5 col-md-3 col-lg-2 vcenter">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="row">

                            <div class="box box-first box-green">
                                <span id="assigned-count"></span>
                                <strong>Assigned Orders</strong>
                            </div>
                            <div class="box box-green">
                                <span id='completed-count'></span>
                                <strong>Delivered Orders</strong>
                            </div>
                            <div class="box box-light-blue">
                                <span>$  <span id="delivered-invoiced"></span></span>
                                <strong>Delivered Invoiced</strong>
                            </div>
                            <div class="box box-light-blue">
                                <span>$ <span id='delivered-paid'></span></span>
                                <strong>Delivered Paid</strong>
                            </div>
                            <div class="box box-light-blue">
                                <span>$  <span id="delivered-margin"></span></span>
                                <strong>Delivered Margin</strong>
                            </div>
                            <div class="box box-blue">
                                <span id="percent-collected"></span>
                                <strong>Collected</strong>
                            </div>
                            <div class="box box-blue" style="padding-right: 5px;">
                                <div class="small-caption">Credit Card <b><span id="credit-card"></span>&nbsp;<small id="credit-card-percent"></small></b></div>
                                <div class="small-caption">Check <b><span id="delivered-check"></span>&nbsp;<small id="delivered-check-percent"></small></b></div>
                                <div class="small-caption">Invoiced <b><span id="delivered-invoiced-count"></span>&nbsp;<small id="delivered-invoiced-count-percent"></small></b></div>
                                <div class="small-caption">COD <b><span id="delivered-cod"></span>&nbsp;<small id="delivered-cod-percent"></small></b></div>
                            </div>

                        </div>           
                    </div>
                </div>

            </div>

        </div>

        <div class="col-lg-10">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h1><b id='current_time'></b></h1>
                    <div class="panel-body panel-body-table">
                        <div class="span12">
                            <div id="orders-placed" style="width:210px;height:150px;float:left;"></div>
                            <div id="orders-assigned" style="width:210px;height:150px;float:left;"></div>
                            <div id="orders-completed" style="width:210px;height:150px;float:left;"></div>
                            <div id="orders-canceled" style="width:210px;height:150px;float:left;"></div>
                            <div id="orders-qc" style="width:210px;height:150px;float:left;"></div>
                        </div>            
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h1><b id='current_time'></b></h1>
                    <div class="panel-body panel-body-table">
                        <div class="row-fluid">
                            <div class="col-md-12">
                                <div class="caption">Monthly Revenue</div>
                                <p class="caption-desc"><small>(Excludes Temp & Cancelled Orders)</small></p>
                                <div id="monthly-revenue" style="width:100%;"></div>
                            </div>
                        </div>           
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h1><b id='current_time'></b></h1>
                    <div class="panel-body panel-body-table">
                        <div class="row-fluid">
                            <div class="col-md-12">
                              <div class="caption">Outstanding Accounts Receivable</div>
                                <div id="outstanding-accounts-rec" style="width:100%;"></div>
                            </div>
                        </div>           
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-10">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="span12">
                            <div class="caption">Daily Margin</div>
                            <div id="daily-margin" style="width:100%;height:300px;"></div>
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
   <script src="{{ masset('js/modules/admin/statistics/accounting-big/index.js') }}"></script>
@endpush