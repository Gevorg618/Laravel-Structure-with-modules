@extends('admin::layouts.master')

@section('title', 'Appraisal UW Pipeline')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Post Completion Pipelines', 'url' => '#'],
  ['title' => 'Appraisal UW Pipeline', 'url' => route('admin.post-completion-pipelines.appr-uw-pipeline')]
],
'actions' => [
  ['title' => 'View UW Statistics', 'url' => route('admin.post-completion-pipelines.appr-uw-pipeline.statistics')],
  ['title' => 'View UW Condition Stats', 'url' => route('admin.post-completion-pipelines.appr-uw-pipeline.uw-report')],
]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="row content_top">
                            <div class="col-md-10">
                                <span class="qc-locked">Locked</span> |
                                <span class="row-is-saved">Saved</span> |
                                <span class="row-is-purchased">Purchase</span> |
                                <span class="row-is-hold">On Hold</span> |
                                <span class="row-is-cu-risk-hold">CU Risk Hold</span> |
                                <span class="row-is-rush">Rush</span>
                                @foreach($teams as $team)
                                    @if($team->qc_uw_pipeline_color)
                                        | <span class="{{ sprintf("row-team-color-%s", $team->id)}}">{{ $team->descrip }}</span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="table-responsive">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#approval">Awaiting Approval ({{$awaitingApprovalCount}})</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#pending">Pending Corrections ({{$pendingCorrectionsCount}})</a>
                                </li>
                            </ul>
                            <div class="tab-content" style="margin-top: 30px;">
                                <div id="approval" class="tab-pane fade in active">
                                    {!!$awaitingApprovalView!!}
                                </div>
                                <div id="pending" class="tab-pane fade">
                                    {!!$pendingCorrectionsView!!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('style')
    <link rel="stylesheet" href="{{ masset('css/appraisal/appr_uw_pipeline/index.css') }}">
    <style>
        @foreach($teams as $team)
            @if($team->qc_uw_pipeline_color)
                {{sprintf(".row-team-color-%s {background-color:%s;}\n", $team->id, $team->qc_uw_pipeline_color)}}
            @endif
        @endforeach
    </style>
@endpush

