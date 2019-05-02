<div class="form-checklist-options">
    <div class="row form-checklist-option-mark-approve form-option-checklist ">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Additional Data & Final Appraised Value</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            @if($isFinalAppraisedValueRequired)
                                <div class="alert alert-danger">Final Appraised Value field is required</div>
                            @endif
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input id="final_appraised_value" name="final_appraised_value" class="form-control" placeholder="Final Appraised Value" value="{{$order->final_appraised_value}}"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            @if($qcDataCollection && count($qcDataCollection))
                                <p class="muted">Please answer the following questions. Marked with <span style="color:red;">*</span> are required.</p>
                                @foreach($qcDataCollection as $collection)
                                    <div class="form-group">
                                        <label for="collection_{{$collection->id}}" class="col-md-3 control-label">{{$collection->title}}
                                            @if($collection->is_required) 
                                                <span style="color:red"></span>
                                            @endif
                                        </label>
                                        <div class="col-md-9">
                                            @include('admin::post-completion-pipelines.appr-uw-pipeline.partials.view_checklist._qc_data_collection_row', ['order' => $order, 'collection' => $collection])
                                            @if($collection->description)
                                                <span class="help-block">{{$collection->description}}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Mark Approved</h3>
                </div>
                <div class="panel-body qc-mark-approve-attachments">
                    @include('admin::appraisal._qc_attachments', ['orderId' => $order->id, 'orderFiles' => $orderFiles,'name' => 'appraiser_attach'])
                </div>
            </div>
        </div>
    </div>
</div>

