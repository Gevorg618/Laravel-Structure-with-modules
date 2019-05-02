@extends('admin::layouts.master')

@section('title', 'Accounting')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Accounts Payable Manager', 'url' => route('admin.accounting.payable-manager.index')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    {!! Form::open(['route' => 'admin.accounting.payable-manager.download', 'id' => 'form-report-show',  'enctype' => "multipart/form-data"]) !!}
                                    <input type="hidden" id="request_type" name="request_type" value="apprasial" />
                                    <input type='hidden' id='checked_items'  name='checked_items' />
                                    <div class="form-group col-md-6">
                                        <label name="daterange" class="control-label col-lg-3 col-xs-12">Date Range
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {!! Form::text('daterange', null, ['class' => 'form-control daterange', 'id' => 'daterange']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label name="states" class="control-label col-lg-3 col-xs-12"> States
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('states[]', $states, old('states'), ['class' => 'form-control multiselect ', 'id' => 'states', 'multiple' => 'multiple' , 'data-live-search' => "true",
                                             ])}}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label name="client" class="control-label col-lg-3 col-xs-12"> User Groups
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('client[]', $clients, old('client'), ['class' => 'form-control multiselect client', 'id' => 'client' ,'multiple' => 'multiple' , 'data-live-search' => "true",
                                            ]) }}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label name="balance" class="control-label col-lg-3 col-xs-12"> Balance
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('balance', [ '' => 'Nothing selected', 'full' => 'Paid In Full', 'balance' => 'Balance Due', 'refund' => 'Refund'], old('balance'), ['class' => 'form-control', 'id' => 'balance']) }}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label name="status" class="control-label col-lg-3 col-xs-12"> Status
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('status', $statuses, old('status'), ['class' => 'form-control', 'id' => 'status' ]) }}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label name="free_text" class="control-label col-lg-3 col-xs-12"> ID / Name / Email
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            <textarea name="free_text" class="form-control" placeholder="ID, Name or Email Address, One Per Line" id="free_text"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div class="col-lg-12 col-xs-12">
                                            <button type="button" class="btn btn-warning" id="show_button">Show</button>
                                            <button type="submit" class="btn btn-primary hidden" id='download_csv' >Download As CSV</button>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-default hidden danger-zone-revert">
        <p><b><span id="count_records"></span></b> Records are selected. Please double check before clicking the button below. there will be no more confirmation screens or messages.</p>
        <p></p>
        <p id="total"></p>
        <button type="button" class="btn btn-warning apply-payment">Apply Payment</button>
    </div>
    <div class="row" id="nav-content">
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a data-toggle="tab" href="#apprasial" data-type="apprasial"> Appraisals </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#alt" data-type="alt" >MarkIt Value  </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#trimerge" data-type="trimerge" >TriMerge </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#fees" data-type="fees" >Additional Fees </a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="apprasial" class="tab-pane fade in active">
                    @include('admin::accounting.payable-manager.partials.nav-tabs.apprasial')
                </div>
                <div id="alt" class="tab-pane fade">
                    @include('admin::accounting.payable-manager.partials.nav-tabs.alt')
                </div>
                <div id="trimerge" class="tab-pane fade">
                    @include('admin::accounting.payable-manager.partials.nav-tabs.trimerge')
                </div>
                <div id="fees" class="tab-pane fade">
                    @include('admin::accounting.payable-manager.partials.nav-tabs.fees')
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <script src="{{ masset('js/modules/admin/accounting/payable-manager/index.js') }}"></script>
@endpush


