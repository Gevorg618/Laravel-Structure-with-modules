<div role="form" class="form-horizontal realview-box">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">RealView Submission</h3><small>{{$order->address}}</small>
                </div>
                <div class="panel-body">
                    <div class="row realview-result-div">
                        <div class="col-md-6">
                            @if(!$realView || $realView->is_processed ? 'hidden' : '')
                                <div class="realview-progress-bar">
                                    <div class="alert alert-danger">RealView Submission is <b>IN PROGRESS</b> This box will update itself once we get the result from RealView</div>
                                </div>
                            @endif
                            <div class="new-realview-submission ">
                                <div class="alert alert-danger hidden new-realview-submission-errors"></div>
                                <div class="alert alert-success hidden new-realview-submission-success"></div>
                            </div>
                            <h4>Last Submission</h4>
                            @if($realView)
                                <div class="alert alert-info">
                                    Last Submission to RealView was on <strong>{{date('m/d/Y g:i A', $realView->created_date)}}</strong> By <strong>{{getUserFullNameById($realView->created_by)}}</strong>
                                    Report Type Submitted was <strong>{{ucwords($realView->realview_type)}}</strong>
                                </div>
                            @else: 
                                <div class="alert alert-warning">Never Submitted</div>
                            @endif
                            <h4>Submit To RealView</h4>
                            <div class="well">
                                <button type="button" class="btn btn-primary btn-lg btn-block realview-basic realview-submit" data-type="basic" {{$realView || ($realView && !$realView->is_processed) ? 'disabled' : ''}}>Basic</button>
                                @if(checkPerm('can_submit_realview_gold'))
                                    <button type="button" class="btn btn-warning btn-lg btn-block realview-gold realview-submit" data-type="gold" {{in_array($realView->realview_type, ['gold', 'platinum']) || ($realView && !$realView->is_processed) ? 'disabled' : ''}}>Gold</button>
                                @endif
                                @if(checkPerm('can_submit_realview_platinum'))
                                    <button type="button" class="btn btn-danger btn-lg btn-block realview-platinum realview-submit" data-type="platinum" {{in_array($realView->realview_type, ['platinum']) || ($realView && !$realView->is_processed) ? 'disabled' : ''}}>Platinum</button>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if($realView->is_processed)
                                <h4>RealView Documents</h4>
                                @if($pdfReport)
                                    &nbsp; <a href='#' target="_blank" class="btn btn-xs btn-primary">Full PDF Report</a>
                                @endif
                                @if($pdfSummaryReport)
                                    &nbsp; <a href='#' target="_blank" class="btn btn-xs btn-primary">Summary PDF Report</a>
                                @endif
                                @if($xmlReport)
                                    &nbsp; <a href='#' target="_blank" class="btn btn-xs btn-primary">XML Report</a>
                                @endif
                                @if($htmlReport)
                                    &nbsp; <a href='#' target="_blank" class="btn btn-xs btn-primary">HTML Report</a>
                                @endif
                            @endif
                            <br/><br />
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Latest Submission Score</h3>
                                </div>
                                <div class="panel-body realview-previous-submissions">
                                    @if($realView->is_processed)
                                        <table class="table table-condensed table-hover">
                                            <tr class={{$scores ? getScoreStripedClass($scores->compliance) : ''}}>
                                                <th>Compliance</th>
                                                <td>{{$scores->compliance}}</td>
                                            </tr>
                                            <tr class={{$scores ? getScoreStripedClass($scores->credibility) : ''}}>
                                                <th>Credibility</th>
                                                <td>{{$scores->credibility}}</td>
                                            </tr>
                                            <tr class={{$scores ? getScoreStripedClass($scores->complexity) : ''}}>
                                                <th>Complexity</th>
                                                <td>{{$scores->complexity}}</td>
                                            </tr>
                                            <tr class={{$scores ? getScoreStripedClass($scores->overall) : ''}}>
                                                <th>Overall</th>
                                                <td>{{$scores->overall}}</td>
                                            </tr>
                                        </table>
                                    @else
                                        <div class="alert alert-warning">Never Submitted</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        var $realView = <?php echo json_encode($realView); ?>;
        var $inProcess = <?php echo ($realView && !$realView->is_processed) ? 'true' : 'false'; ?>;
        var $orderId = '<?php echo $order->id; ?>';
    </script>
@endpush
@push('scripts')
    <script src="{{ masset('js/appraisal/appr_uw_pipeline/uw_checklist_realView.js')}}"></script>
@endpush
