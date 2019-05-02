@extends('admin::layouts.master')

@section('title', 'Appraisal UW Pipeline')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => $order->address. ' - #'. $order->id, 'url' => '#']
],
'actions' => [
  ['title' => 'Back To Pipeline', 'url' => route('admin.post-completion-pipelines.appr-uw-pipeline')],
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
                            <table class="table table-striped table-bordered table-hover">
                                <tr>
                                    <th>Placed</th>
                                    <th>Completed</th>
                                    <th>Client</th>
                                    <th>Appraiser</th>
                                    <th>Appraisal Type</th>
                                    <th>Loan Type</th>
                                    <th>Final Value</th>
                                </tr>
                                <tr>
                                    <td>{{date('m/d/Y H:i', strtotime($order->ordereddate))}}</td>
                                    <td>{{date('m/d/Y H:i', strtotime($order->completed))}}</td>
                                    <td>{{$order->group_descrip}}</td>
                                    <td>{{$order->appr_name}}</td>
                                    <td>{{$order->type_name}}</td>
                                    <td>{{$order->loan_type}}</td>
                                    <td>{{money_format('%.2n', floatval(str_replace(',', '', $order->final_appraised_value)))}}</td>
                                </tr>
                            </table>
                            <div class="alert alert-info">QC Checklist Type: {!!$order->qc_type!!}</div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <a href='#' class="btn btn-xs btn-primary">Order Details</a>
                                <a href="{{route('admin.post-completion-pipelines.appr-uw-pipeline.uw-conditions', ['id' => $order->id])}}" target="_blank" class="btn btn-xs btn-primary">UW Conditions</a>
                                @if($order->final_appraisal)
                                    <a href="#" class="btn btn-xs btn-primary">Final Report</a>
                                @endif
                                @if($order->previous_report)
                                    <a href="#" class="btn btn-xs btn-primary">Previous Report</a>
                                @endif
                                <hr/>
                            </div>
                        </div>

                        @if($notes)
                            @include('admin::post-completion-pipelines.appr-uw-pipeline.partials.view_checklist._notes', ['notes' => $notes])
                        @endif

                        <form role="form" class="form-horizontal" method='post' action='{{route('admin.post-completion-pipelines.appr-uw-pipeline.save-pipeline', ['id' => $record->id])}}'>
                            {{ csrf_field() }}
                            <input type='hidden' name='id' value='{{$order->id}}' />
                            <input type='hidden' name='action' value='view-checklist' />
                            <input type='hidden' name='total_timer_field' id='total_timer_field' value='{{(int)$totalTime}}' />
                            <input type='hidden' name='current_timer_field' id='current_timer_field' value='' />
                            <input type='hidden' name='start_time' id='start_time' value='{{time()}}' />

                            @include('admin::post-completion-pipelines.appr-uw-pipeline.partials.view_checklist._review_options', ['order' => $order])
                            @include('admin::post-completion-pipelines.appr-uw-pipeline.partials.view_checklist._checklist_visibility')
                            @if($pendingConditions)
                                @include('admin::post-completion-pipelines.appr-uw-pipeline.partials.view_checklist._pending_conditions', ['pendingConditions' => $pendingConditions])
                            @endif
                            @if($generalChecklist)
                                @include('admin::post-completion-pipelines.appr-uw-pipeline.partials.view_checklist._general_checklist', ['generalChecklist' => $generalChecklist, 'order' => $order ])
                            @endif
                            @if($UCDPUnits || $EADUnits || $realView)
                                @include('admin::post-completion-pipelines.appr-uw-pipeline.partials.view_checklist._submissions', ['UCDPUnits' => $UCDPUnits, 'EADUnits' => $EADUnits, 'realView' => $realView])
                            @endif

                            @include('admin::post-completion-pipelines.appr-uw-pipeline.partials.view_checklist._additional_final_value', ['order' => $order ])
                            @include('admin::post-completion-pipelines.appr-uw-pipeline.partials.view_checklist._send_back_appraiser', ['order' => $order ])
                            @include('admin::post-completion-pipelines.appr-uw-pipeline.partials.view_checklist._reviewers_data', ['order' => $order, 'record' => $record ])
                            @include('admin::post-completion-pipelines.appr-uw-pipeline.partials.view_checklist._options_buttons', ['order' => $order, 'record' => $record ])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <script src="{{ masset('js/appraisal/appr_uw_pipeline/uw_checklist.js')}}"></script>
@endpush
