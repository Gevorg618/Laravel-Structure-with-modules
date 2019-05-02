@extends('admin::layouts.master')

@section('title', 'Reconsideration Pipeline')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Post Completion Pipelines', 'url' => '#'],
        ['title' => 'Reconsideration Pipeline', 'url' => route('admin.post-completion-pipelines.review-pipeline')],
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
                                    <a data-toggle="tab" href="#under_review">Under Review</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#waiting_for_approval">Waiting For Approval</a>
                                </li>
                            </ul>
                            <div class="tab-content" style="margin-top: 25px;">
                                <div id="under_review" class="tab-pane fade in active">
                                    {!! $underReviewView !!}
                                </div>
                                <div id="waiting_for_approval" class="tab-pane fade">
                                    {!! $waitingForApprovalView !!}
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
    <script type="text/javascript" src="{{ masset('js/appraisal/reconsideration_pipeline/index.js') }}"></script>
@endpush
