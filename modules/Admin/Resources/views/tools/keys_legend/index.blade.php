@extends('admin::layouts.master')

@section('title', 'Keys Legend')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Control Panel', 'url' => '#'],
        ['title' => 'Keys Legend', 'url' => route('admin.tools.keys-legend')]
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
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#order_legend">Order Legend</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#user_legend">User Legend</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#group_legend">Group Legend</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#lender_legend">Lender Legend</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#app_legend">Apprasier Legend</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#amc_reg_legend">AMC Registration Legend</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#appr_data_coll">Appraisal QC Data Collection</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="order_legend" class="tab-pane fade in active">
                                    {!! $orderLegendView !!}
                                </div>
                                <div id="user_legend" class="tab-pane fade">
                                    {!! $userLegendView !!}
                                </div>
                                <div id="group_legend" class="tab-pane fade">
                                    {!! $groupLegendView !!}
                                </div>
                                <div id="lender_legend" class="tab-pane fade">
                                    {!! $lenderLegendView !!}
                                </div>
                                <div id="app_legend" class="tab-pane fade">
                                    {!! $apprLegendView !!}
                                </div>
                                <div id="amc_reg_legend" class="tab-pane fade">
                                    {!! $amcRegLegendView !!}
                                </div>
                                <div id="appr_data_coll" class="tab-pane fade">
                                    {!! $apprDataCollView !!}
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
    <link href="{{ masset('css/tools/keys_legend/index.css') }}" rel="stylesheet" type="text/css">
@endpush
