@extends('admin::layouts.master')

@section('title', 'Final Appraisals to be Mailed')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Post Completion Pipelines', 'url' => '#'],
        ['title' => 'Final Appraisals to be Mailed', 'url' => route('admin.appraisal-pipeline.escalated-orders')]
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
                                    <a data-toggle="tab" href="#pending">Pending ({{$pendingCount}})</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#sent">Sent ({{$sentCount}})</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#delivered">Delivered ({{$deliveredCount}})</a>
                                </li>
                            </ul>
                            <div class="tab-content" style="margin-top: 30px;">
                                <div id="pending" class="tab-pane fade in active">
                                    {!! $pendingView !!}
                                </div>
                                <div id="sent" class="tab-pane fade">
                                    {!! $sentView !!}
                                </div>
                                <div id="delivered" class="tab-pane fade">
                                    {!! $deliveredView !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="view_mail_record" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
    <div class="modal fade" id="mark_sent_mail_record" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
    <div class="modal fade" id="edit_tracking_number" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
@stop

@push('scripts')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css"/>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.0.2/dist/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="{{ masset('js/appraisal/mail_pipeline/index.js') }}"></script>
@endpush

