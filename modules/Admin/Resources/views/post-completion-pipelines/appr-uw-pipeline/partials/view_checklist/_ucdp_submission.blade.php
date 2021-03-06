<div role="form" class="form-horizontal ucdp-box">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">UCDP Submission </h3><small>{{$order->address}}</small>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12" id="pendingSubmission">
                            @if($pendingSubmission = $order->ucdpSubmissions)
                                @include('admin::post-completion-pipelines.appr-uw-pipeline.partials.view_checklist._third_party_pending_submission', ['order' => $order, 'row' => $pendingSubmission])
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="ucdp-progress-bar hidden">
                                <div class="alert alert-danger">UCDP Submission is <b>IN PROGRESS</b> please do not refresh the page and hold.</div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                        <span>0</span>% Complete
                                    </div>
                                </div>
                            </div>
                            <div class="new-ucdp-submission">
                                <div class="alert alert-danger hidden new-ucdp-submission-errors"></div>
                                <div class="alert alert-success hidden new-ucdp-submission-success"></div>
                                <div class="form-group business-form-group">
                                    <label for="business_unit" class="col-md-4 control-label">Business Unit</label>
                                    <div class="col-md-8">
                                        <select name="business_unit" id="business_unit" class="form-control">
                                            <option value="">-- Select Business Unit --</option>
                                            @foreach($businessUnitsList as $value)
                                                <option value="{{$value->id}} {{$selectedUnit->id == $value->id ? 'selected' : ''}}"></option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group fnm-form-group ssn_group fnm_group hidden">
                                    <label for="fnm_ssn" class="col-md-4 control-label">Fannie Mae SSN</label>
                                    <div class="col-md-8">
                                        @foreach($fnmList as $value)
                                            <option value="{{$value->id}} {{$selectedFNM->id == $value->id ? 'selected' : ''}}"></option>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group fre-form-group ssn_group fre_group hidden">
                                    <label for="fre_ssn" class="col-md-4 control-label">Freddie Mac SSN</label>
                                    <div class="col-md-8">
                                        @foreach($freList as $value)
                                            <option value="{{$value->id}} {{$selectedFRE->id == $value->id ? 'selected' : ''}}"></option>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group loan-form-group">
                                    <label for="loan_number" class="col-md-4 control-label">Lender Loan #</label>
                                    <div class="col-md-8">
                                        <input type="text" name="loan_number" id="loan_number" value="{{$order->loanrefnum ?: $order->id}}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group document-file-id">
                                    <label for="docFileId" class="col-md-4 control-label">DocFileId</label>
                                    <div class="col-md-8">
                                        <input type="text" name="docFileId" id="docFileId" value="{{$orderUCDPSubmission ? $orderUCDPSubmission->doc_file_id : ''}}" class="form-control">
                                        <span class="help-block">If this is a resubmission that was previsouly submitted, Please enter the DocFileId</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-12">
                                        @if($finalReportXML)
                                            <div class="alert alert-danger"><b>Note!</b> Once you submit the form it'll be added to the queue, you can navigate away from this page and come back at a later time to see the result. It can take up to 15 minutes for one to be processed.</div>
                                            <button type="button" value='Submit' name="submit" id="ucdp-new-submit-button" class="btn btn-danger">Submit New Report</button>
                                        @else
                                            <div class="alert alert-danger">We could not find the Final Appraisal XML Document.</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <a href='#' class="btn btn-xs btn-primary">Order Details</a>
                            <br /><br />
                            <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Previous Submission</h3>
                            </div>
                            <div class="panel-body ucdp-previous-submissions">
                                @include('admin::post-completion-pipelines.appr-uw-pipeline.partials.view_checklist._ucdp_previous_submission', ['order' => $order])
                            </div>
                            </div>
                        </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>