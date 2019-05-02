@extends('admin::layouts.master')

@section('title', 'FNC')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Integrations', 'url' => '#'],
        ['title' => 'FNC', 'url' => route('admin.integrations.fnc')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#statuses">Statuses</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#appraisal_types">Appraisal Types</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#loan_types">Loan Types</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#loan_reasons">Loan Reasons</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#property_types">Property Types</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="statuses" class="tab-pane fade in active">
                                    {!! $statusesView !!}
                                </div>
                                <div id="appraisal_types" class="tab-pane fade">
                                    {!! $apprTypesView !!}
                                </div>
                                <div id="loan_types" class="tab-pane fade">
                                    {!! $loanTypesView !!}
                                </div>
                                <div id="loan_reasons" class="tab-pane fade">
                                    {!! $loanReasonView !!}
                                </div>
                                <div id="property_types" class="tab-pane fade">
                                    {!! $propertyTypes !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


@push('scripts')
    <link href="{{ masset('css/integrations/mercury_network/index.css') }}" rel="stylesheet" type="text/css" />
@endpush