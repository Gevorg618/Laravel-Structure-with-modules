@extends('admin::layouts.master')

@section('title', 'Update New Group')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Admin User', 'url' => '#'],
      ['title' => 'Client Settings', 'url' => route('admin.management.client.settings')],
      ['title' => 'Edit  Group', 'url' => '']
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div>
                            <div class="tabbable tabs-left">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#basic_info" data-toggle="tab">Basic Info</a>
                                    </li>
                                    <li>
                                        <a href="#amc-management" data-toggle="tab">AMC Management</a>
                                    </li>
                                    <li>
                                        <a href="#orderoptions" data-toggle="tab">Order Options</a>
                                    </li>
                                    <li>
                                        <a href="#volume" data-toggle="tab">Volume</a>
                                    </li>
                                    <li>
                                        <a href="#notes" data-toggle="tab">Admin Notes</a>
                                    </li>
                                    <li>
                                        <a href="#user_management" data-toggle="tab">User Management</a>
                                    </li>
                                    <li>
                                        <a href="#fees" data-toggle="tab">Fees</a>
                                    </li>
                                    <li>
                                        <a href="#operations" data-toggle="tab">Operations</a>
                                    </li>

                                    <li>
                                        <a href="#accounting" data-toggle="tab">Accounting</a>
                                    </li>
                                    <li>
                                        <a href="#documents" data-toggle="tab">Documents</a>
                                    </li>

                                    <li>
                                        <a href="#orders" data-toggle="tab">Orders</a>
                                    </li>
                                    <li>
                                        <a href="#integrations" data-toggle="tab">Integrations</a>
                                    </li>

                                    <li>
                                        <a href="#logs" data-toggle="tab">Logs</a>
                                    </li>

                                    <li>
                                        <a href="#history" data-toggle="tab">History</a>
                                    </li>

                                    <li>
                                        <a href="#sales" data-toggle="tab">Sales</a>
                                    </li>

                                    <li>
                                        <a href="#groupnotes" data-toggle="tab">Notes</a>
                                    </li>

                                </ul>
                                {{ Form::model($client, ['route' => ['admin.management.client.update', $client->id], 'method' => 'put', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal', 'id'=> 'form-update']) }}
                                {{csrf_field()}}
                                <input type="hidden" name="_method" value="PUT">
                                <div class="tab-content">
                                    @if(!empty($errors->first()))
                                        <div class="col-md-12 row" style="margin-top: 15px;">
                                            <div class="row col-md-7">
                                                <div class="alert alert-danger alert-dismissible" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert"
                                                            aria-label="Close"><span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <span>{{ $errors->first() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="tab-pane active" id="basic_info">
                                        @include('admin::management.client_settings.partials._form', ['button_label' => 'Update'])
                                    </div>

                                    <div class="tab-pane" id="amc-management">
                                        @include('admin::management.client_settings.tabs._amc_management')
                                    </div>

                                    <div class="tab-pane" id="orderoptions">
                                        @include('admin::management.client_settings.tabs._order_options')
                                    </div>

                                    <div class="tab-pane" id="volume">
                                        @include('admin::management.client_settings.tabs._volume')
                                    </div>

                                    <div class="tab-pane" id="notes">
                                        @include('admin::management.client_settings.tabs._admin_notes')
                                    </div>

                                    <div class="tab-pane" id="user_management">
                                        @include('admin::management.client_settings.tabs._user_management')
                                    </div>

                                    <div class="tab-pane" id="fees">
                                        @include('admin::management.client_settings.tabs._fees')
                                    </div>

                                    <div class="tab-pane" id="operations">
                                        @include('admin::management.client_settings.tabs._operations')
                                    </div>

                                    <div class="tab-pane" id="accounting">
                                        @include('admin::management.client_settings.tabs._accounting')
                                    </div>

                                    <div class="tab-pane" id="documents">
                                        @include('admin::management.client_settings.tabs._documents')
                                    </div>

                                    <div class="tab-pane" id="orders">
                                        @include('admin::management.client_settings.tabs._orders')
                                    </div>

                                    <div class="tab-pane" id="integrations">
                                        @include('admin::management.client_settings.tabs._integrations')
                                    </div>

                                    <div class="tab-pane" id="logs">
                                        @include('admin::management.client_settings.tabs._logs')
                                    </div>

                                    <div class="tab-pane" id="history">
                                        @include('admin::management.client_settings.tabs._history')
                                    </div>

                                    <div class="tab-pane" id="sales">
                                        @include('admin::management.client_settings.tabs._sales')
                                    </div>

                                    <div class="tab-pane" id="groupnotes">
                                        @include('admin::management.client_settings.tabs._note')
                                    </div>
                                </div>
                                {{ Form::close() }}

                                <div class="col-md-12 _footer">
                                    <button type="button" class="btn btn-success" id="btn-update">Save</button>
                                    <button type="button" class="btn btn-danger" id="btn-active">
                                        @if($client->active) Disable Group @else Enable Group @endif
                                    </button>
                                </div>
                                {{ Form::open(['route' => 'admin.management.client.change.active', 'method' => 'post', 'class' => 'form-horizontal', 'id' => 'form-active']) }}
                                <input type="hidden" name="id" value="{{$client->id}}">
                                <input type="hidden" name="active" @if($client->active) value="0"
                                       @else($client->active ) value="1" @endif>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@push('style')
    <link href="{{ masset('js/bootstrap-multiselect/dist/css/bootstrap-multiselect.css') }}" rel="stylesheet"/>
    <link href="{{ masset('css/management/client_settings/edit.css') }}" rel="stylesheet"/>
@endpush

@push('scripts')
    <script src="{{ masset('js/bootstrap-multiselect/dist/js/bootstrap-multiselect.js') }}"></script>
    <script>
        $("#btn-update").click(function () {
            $("#form-update").submit();
        });

        $("#btn-active").click(function () {
            $("#form-active").submit();
        })
    </script>
@endpush

