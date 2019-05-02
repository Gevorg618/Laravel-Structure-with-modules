@extends('admin::layouts.master')

@section('title', 'System Statistics')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Statistics & User Tracking', 'url' => '#'],
        ['title' => 'System Statistics', 'url' => route('admin.statistics.system-statistics')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">

                        <div class="col-md-12">
                            <div class="table-responsive col-md-4">
                                <table class="table table-striped table-bordered table-hover">
                                    <caption><h4>Users</h4></caption>
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Count</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $key => $value)
                                            <tr>
                                                <td>{{$key}}</td>
                                                <td>{{number_format($value)}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive col-md-4">
                                <table class="table table-striped table-bordered table-hover">
                                    <caption><h4>Orders</h4></caption>
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Count</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $key => $value)
                                            <tr>
                                                <td>{{$key}}</td>
                                                <td>{{number_format($value)}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive col-md-4">
                                <table class="table table-striped table-bordered table-hover">
                                    <caption><h4>Order Logs</h4></caption>
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Count</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orderLogs as $key => $value)
                                            <tr>
                                                <td>{{$key}}</td>
                                                <td>{{number_format($value)}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="table-responsive col-md-4">
                                <table class="table table-striped table-bordered table-hover">
                                    <caption><h4>Order Files</h4></caption>
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Count</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orderFiles as $key => $value)
                                            <tr>
                                                <td>{{$key}}</td>
                                                <td>{{number_format($value)}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive col-md-4">
                                <table class="table table-striped table-bordered table-hover">
                                    <caption><h4>Other Files</h4></caption>
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Count</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($otherFiles as $key => $value)
                                            <tr>
                                                <td>{{$key}}</td>
                                                <td>{{number_format($value)}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive col-md-4">
                                <table class="table table-striped table-bordered table-hover">
                                    <caption><h4>File Sizes</h4></caption>
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Count</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($fileSizes as $key => $value)
                                            <tr>
                                                <td>{{$key}}</td>
                                                <td>{{formatFileSize($value, 2)}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="table-responsive col-md-4">
                                <table class="table table-striped table-bordered table-hover">
                                    <caption><h4>Database Info</h4></caption>
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Count</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dbInfo as $key => $value)
                                            <tr>
                                                @if($key !== 'Size')
                                                    <td>{{$key}}</td>
                                                    <td>{{number_format($value)}}</td>
                                                @else
                                                    <td>{{$key}}</td>
                                                    <td>{{formatFileSize($value, 2)}}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive col-md-4">
                                <table class="table table-striped table-bordered table-hover">
                                    <caption><h4>Tickets</h4></caption>
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Count</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{key($tickets)}}</td>
                                            <td>{{number_format($tickets[key($tickets)])}}</td>
                                        </tr>
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
