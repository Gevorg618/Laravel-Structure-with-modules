@extends('admin::layouts.master')

@section('title', 'Client')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Admin User', 'url' => '#'],
        ['title' => 'Client Settings', 'url' => route('admin.management.client.settings')]
    ],
    'actions' => [
        ['title' => 'Add Client', 'url' => route('admin.management.client.create')],
    ]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="row">
                            <div>
                                <form class="form-inline" role="form">
                                    <div class="form-group filters_form">
                                        <label>Filters:</label>
                                        <select id="sales_rep" name="sales_rep"
                                                class="form-control filter-change bootstrap-multiselect">
                                            <option value="">-- All Sales --</option>
                                            <optgroup label="Sales">
                                                @foreach($sales as $key => $value)
                                                    <option value="{{$key}}">{{$value}}</option>
                                                @endforeach
                                            </optgroup>
                                            <optgroup label="Super Users">
                                                @foreach($superUsers as $key => $value)
                                                    <option value="{{$key}}">{{$value}}</option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                        <select id="state" name="state" class="form-control filter-change bootstrap-multiselect">
                                            <option value="">-- All States --</option>
                                            @foreach($states as $state)
                                                <option value="{{$state->abbr}}">{{$state->state}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Sales Person</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input value="{{route('admin.management.client.settings.data')}}" type="hidden" name="client_data_url">
    </div>
@stop

@push('style')
    <link href="{{ masset('js/bootstrap-multiselect/dist/css/bootstrap-multiselect.css') }}" rel="stylesheet" />
    <style>
        table {
            width: 100% !important;
        }
        .multiselect-container{
            max-height: 400px;
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ masset('js/bootstrap-multiselect/dist/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/management/client_settings/index.js') }}"></script>
@endpush
