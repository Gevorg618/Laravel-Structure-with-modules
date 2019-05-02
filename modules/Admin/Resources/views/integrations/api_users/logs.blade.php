@extends('admin::layouts.master')

@section('title', 'API User Logs')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Integrations', 'url' => '#'],
        ['title' => 'API Users', 'url' => route('admin.integrations.api-users')],
        ['title' => 'API User Logs', 'url' => '#']
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <h2>{{$apiUser->title}} {{$apiUser->company}} ({{ number_format($count)}} Logs)</h2>
                        @if(!empty($errors->first()))
                            <div class="col-md-12" style="margin-top: 15px;">
                                <div class="row col-md-6">
                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <span>{{ $errors->first() }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-12 search_content">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Search</h3>
                                </div>
                                <div class="panel-body">
                                    <form method="GET" class="form-horizontal" action="{{route('admin.integrations.api-users.search')}}">
                                        <input type="hidden" name="id" value="{{$apiUser->id}}">
                                        <table width="100%">
                                            <tr>
                                                <td>
                                                    <input type="text" class="form-control" name="term" value="" placeholder="Search Term">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control date_from" name="from" value="" placeholder="From">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control date_to" name="to" value="" placeholder="To">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="per_page" value="" placeholder="Records Per Page">
                                                </td>
                                                <td>
                                                    <div class="col-md-4">
                                                        <button type="submit" class="btn btn-primary">Search</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 logs_content">
                            @foreach($logs as $log)
                                <div class="panel panel-{{$log->is_success ? 'success' : 'danger'}}">
                                    <div class="panel-heading">
                                        <h3 class="panel-title pull-left">{{date('m/d/Y H:i:s', $log->created)}} ({{$log->code}})</h3>
                                        <div class="pull-right">
                                            <button data-id="{{$log->id}}" class="toggle-content btn btn-default btn-xs">
                                                <i class="fa fa-plus"></i>
                                                <span>Show</span>
                                            </button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="panel-body">
                                        <pre>{{$log->request}}</pre>
                                        <pre class="full-log-content hidden"></pre>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-md-12">
                            {{$logs->appends(Request::capture()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <link rel="stylesheet" type="text/css" href="{{masset('css/integrations/api_users/logs.css')}}">
    <script type="text/javascript" src="{{masset('js/integrations/api_users/logs.js')}}"></script>
@endpush
