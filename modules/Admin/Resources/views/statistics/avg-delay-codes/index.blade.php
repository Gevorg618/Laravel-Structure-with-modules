@extends('admin::layouts.master')

@section('title', 'Average Delay Codes')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Statistics & User Tracking', 'url' => '#'],
      ['title' => 'Average Delay Codes', 'url' => route('admin.statistics.avg-delay-codes')]
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
                            <div class="container">
                                <form method='POST' action="{{route('admin.statistics.avg-delay-codes')}}">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="col-md-12">
                                                    <label for="" class="col-md-3">Date <span class="required"></span></label>
                                                    <div class="col-md-6">
                                                        <input type="text" name="daterange" value="" class="form-control"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 margin_top">
                                                    <label for="" class="col-md-3">Date Type</label>
                                                    <div class="col-md-6">
                                                        <select type="text" name="datetype" class="form-control">
                                                            <option value="ordereddate">Ordered</option>
                                                            <option value="accepteddate">Accepted</option>
                                                            <option value="date_delivered">Delivered</option>
                                                            <option value="date_uw_received">UW Received</option>
                                                            <option value="date_uw_completed">UW Completed</option>
                                                            <option value="date_first_paid">Date First Paid</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col-md-12">
                                                    <label for="" class="col-md-3">Client</label>
                                                    <div class="col-md-6">
                                                        <select type="text" name="client[]" class="form-control multiselect bootstrap-multiselect" multiple="multiple">
                                                            @foreach ($clients as $client)
                                                                <option value="{{$client->id}}">{{$client->descrip}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 margin_top">
                                                    <label for="" class="col-md-3">Appraisal Type</label>
                                                    <div class="col-md-6">
                                                        <select type="text" name="apprtypes[]" class="form-control multiselect bootstrap-multiselect" multiple="multiple">
                                                            @foreach ($types as $type)
                                                                <option value="{{$type->id}}">{{$type->descrip}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type='reset' class="btn col-md-offset-4" id='reset_filters'>Reset</button>
                                        <button type='submit' class="btn btn-primary" id='submit'>Submit</button>
                                    </div>
                                    <div class="clear"></div>
                                </form>
                                <table class="table table-striped table-bordered table-hover parent_table" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>Delay Code</th>
                                            <th>Average Days</th>
                                            <th>Orders</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rows as $row)
                                            <tr class="avgshow" id="avgshow_{{$row->id}}">
                                                <td>{{$row->name}}</td>
                                                <td>{{$row->average}}</td>
                                                <td>{{$row->total}}</td>
                                            </tr>
                                            @if($submit)
                                                <tr style="display:none;"></tr>
                                                <tr style="display:none;" class="avghide" id="avghide_{{$row->id}}">
                                                    <td colspan="3">
                                                        <table class="table table-striped table-bordered table-hover">
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Ordered</th>
                                                                <th>Delivred</th>
                                                                <th>Client</th>
                                                                <th>Status</th>
                                                                <th>Team</th>
                                                                <th>Delayed Time</th>
                                                            </tr>
                                                            @if($row->orders)
                                                                @foreach($row->orders as $order)
                                                                    <tr>
                                                                        <td>
                                                                            <a href='#' target='_blank'>{{ $order->id }}</a>
                                                                        </td>
                                                                        <td>{{ date('m/d/Y', strtotime($order->ordereddate)) }}</td>
                                                                        <td>{{ date('m/d/Y', strtotime($order->date_delivered)) }}</td>
                                                                        <td>{{ $order->company ? $order->company : 'N/A' }}</td>
                                                                        <td>{{ $order->descrip ? $order->descrip : 'N/A' }}</td>
                                                                        <td>{{ $order->team_title ? $order->team_title : 'N/A' }}</td>
                                                                        <td>{{ $order->total_days }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </table>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('style')
    <link href="{{ masset('js/bootstrap-multiselect/dist/css/bootstrap-multiselect.css') }}" rel="stylesheet" />
    <style>
        .margin_top {
            margin-top: 15px;
        }
        .parent_table {
            margin-top: 120px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ masset('js/bootstrap-multiselect/dist/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/statistics/avg_delay_codes/index.js') }}"></script>
@endpush
