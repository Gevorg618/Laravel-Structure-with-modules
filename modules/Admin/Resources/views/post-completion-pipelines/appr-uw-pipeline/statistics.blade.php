@extends('admin::layouts.master')

@section('title', 'View UW Statistics')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Post Completion Pipelines', 'url' => '#'],
  ['title' => 'Appraisal UW Pipeline', 'url' => route('admin.post-completion-pipelines.appr-uw-pipeline')],
  ['title' => 'View UW Statistics', 'url' => route('admin.post-completion-pipelines.appr-uw-pipeline.statistics')]
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
                                <div class="col-md-12">
                                    <form action="{{route('admin.post-completion-pipelines.appr-uw-pipeline.statistics')}}" method="POST">
                                        {{ csrf_field() }}
                                        <div class="form-group row">
                                            <div class="col-md-1">
                                                <label for="">Date:</label>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="col-md-2">
                                                    <label for="date_from" style="line-height:2;" class="required">From</label>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" name="date_from" id="date_from" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="col-md-1">
                                                    <label for="date_to" style="line-height:2;" class="required">To</label>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" name="date_to" id="date_to" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="text-align: center;">
                                            <div class="col-md-12">
                                                <button type="reset" class="btn btn-success">Reset</button>
                                                <button type="submit" class="btn btn-success">Show</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            @if(isset($daily) && count($daily))
                                <h2>Checklist Statistics</h2>
                                <table class="table table-striped table-bordered table-hover" id="daily-order-rows">
                                    <thead>
                                        <th>Admin</th>
                                        <th>1st Approved</th>
                                        <th>AVG Time</th>
                                        <th>Sent Back</th>
                                        <th>AVG Time</th>
                                        <th>2nd Approved</th>
                                        <th>AVG Time</th>
                                        <th>Total</th>
                                        <th>Total Time</th>
                                    </thead>
                                    <tbody>
                                        @if($daily['list'] && count($daily['list']))
                                            @foreach($daily['list'] as $userId => $row)
                                                <tr class="order-tr-row">
                                                    <td>
                                                        <a href='#'>{{ $row['user_name'] }}</a>&nbsp;
                                                        <a href='#' class='view-user-stats' data-id='{{$userId}}' data-from='{{$from}}' data-to='{{$to}}' rel='tooltip' title='Click to view user stats'>
                                                            <i class="fa fa-search"></i>
                                                        </a>
                                                    </td>
                                                    <td>{{ $row['first'] }}</td>
                                                    <td>{{ implode(':', secondsToTime($row['avg_first'])) }}</td>
                                                    <td>{{ $row['back'] }}</td>
                                                    <td>{{ implode(':', secondsToTime($row['avg_back'])) }}</td>
                                                    <td>{{ $row['second'] }}</td>
                                                    <td>{{ implode(':', secondsToTime($row['avg_second'])) }}</td>
                                                    <td>{{ $row['total'] }}</td>
                                                    <td>{{ implode(':', secondsToTime($row['total_time'])) }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>{{ $daily['totals']['first'] }}</th>
                                                <th>{{ implode(':', secondsToTime($daily['totals']['avg_first'])) }}</th>
                                                <th>{{ $daily['totals']['back'] }}</th>
                                                <th>{{ implode(':', secondsToTime($daily['totals']['avg_back'])) }}</th>
                                                <th>{{ $daily['totals']['second'] }}</th>
                                                <th>{{ implode(':', secondsToTime($daily['totals']['avg_second'])) }}</th>
                                                <th>{{ $daily['totals']['total'] }}</th>
                                                <th>{{ implode(':', secondsToTime($daily['totals']['total_time'])) }}</th>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="11">No Records To Show.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            @endif


                            @if(isset($conditionUsers) && count($conditionUsers))
                                <h2>UW Condition Statistics</h2>
                                <table class="table table-striped table-bordered table-hover" id="daily-order-rows">
                                    <thead>
                                        <th>Admin</th>
                                        <th>Order Conditions</th>
                                        <th>Total Conditions</th>
                                    </thead>
                                    <tbody>
                                            @php
                                                $totalUniqueConditions = 0;
                                                $totalConditions = 0;
                                            @endphp

                                            @foreach($conditionUsers as $userId => $value)
                                                @php
                                                    $totalUniqueConditions += $value['userConditionsDistinct'];
                                                    $totalConditions += $value['userConditions'];
                                                    if($value['userConditions'] <= 0) {
                                                        continue;
                                                    }
                                                @endphp
                                                <tr class="order-tr-row">
                                                    <td>
                                                        <a href='#'>{{$value['user_name']}}</a>&nbsp;
                                                        <a href='#' class='view-user-condition-stats' data-id='{{$userId}}' data-from='{{$from}}' data-to='{{$to}}' rel='tooltip' title='Click to view user stats'>
                                                            <i class="fa fa-search"></i>
                                                        </a>
                                                    </td>
                                                    <td>{{$value['userConditionsDistinct']}}</td>
                                                    <td>{{$value['userConditions']}}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>{{ $totalUniqueConditions }}</th>
                                                <th>{{ $totalConditions }}</th>
                                            </tr>
                                    </tbody>
                                </table>
                            @endif
                        </div>
                        @if(isset($daily['list']) && count($daily['list']))
                            {!!$guageChartsView!!}
                            {!!$dailyPieChartView!!}
                            {!!$dailyComboChartView!!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="view_user_stats" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 id="view_user_stats_title" class="modal-title"></h4>
                </div>
                <div id="view_user_stats_content" class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
@stop

@push('style')
    <link rel="stylesheet" href="{{ masset('css/appraisal/appr_uw_pipeline/statistics.css')}}">
@endpush

@push('scripts')
    <script src="{{ masset('js/appraisal/appr_uw_pipeline/statistics.js')}}"></script>
@endpush
