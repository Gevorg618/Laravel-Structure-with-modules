@extends('admin::layouts.master')

@section('title', 'Bulk Change Tool')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Customizations', 'url' => '#'],
        ['title' => 'Bulk Change Tool', 'url' => route('admin.management.client-bulk-tool')]
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
                                    <a data-toggle="tab" href="#appr">Appraisals</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#loan_types">Loan Types</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#property_types">Property Types</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#loan_reasons">Loan Reasons</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="appr" class="tab-pane fade in active">
                                    {!! $apprView !!}
                                </div>
                                <div id="loan_types" class="tab-pane fade">
                                    {!! $loanTypesView !!}
                                </div>
                                <div id="property_types" class="tab-pane fade">
                                    {!! $propertyTypesView !!}
                                </div>
                                <div id="loan_reasons" class="tab-pane fade">
                                    {!! $loanReasonsView !!}
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
    <link href="{{ masset('js/bootstrap-multiselect/dist/css/bootstrap-multiselect.css') }}" rel="stylesheet" />
    <script src="{{ masset('js/bootstrap-multiselect/dist/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/management/client_bulk_tool/index.js') }}"></script>
@endpush
