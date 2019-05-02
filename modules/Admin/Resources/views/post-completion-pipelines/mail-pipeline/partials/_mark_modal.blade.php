<div class="modal-dialog modal-lg">
    <input type="hidden" id="rowid" value="{{$row->id}}" />
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="mark_sent_mail_record_title">Marking Order Sent</h4>
        </div>
        <div class="modal-body" id="mark_sent_mail_record_content">
            <div class="alert alert-error" style="display:none;" id="log_error_block"></div>
            <div class="alert alert-success" style="display:none;" id="log_ok_block"></div>

            <div class="row">
                <div class="col-md-7">
                    <b>Mail To:</b> <Br />
                    {{ $order->final_appraisal_borrower_name }}<Br />
                    {{ $order->final_appraisal_borrower_address1 }}<br />
                    @if($order->final_appraisal_borrower_address2)
                        {{ $order->final_appraisal_borrower_address2 }}<br />
                    @endif
                    {{$order->final_appraisal_borrower_city}}, {{$order->final_appraisal_borrower_state}} {{$order->final_appraisal_borrower_zip}}
                    <hr />
                    <div class="form-group">
                        <label for="tracking_number" class="col-md-5 control-label">Tracking Number <span class="required"></span></label>
                        <div class="col-md-7">
                            <input type="text" id="tracking_number" name="tracking_number" value="{{$row->tracking_number}}" class="form-control"/>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr />
                        <div class="alert alert-danger hidden"></div>
                        <div class="alert alert-success hidden"></div>
                        <div class="alert alert-info">
                            You can now create a label that will generate a valid USPS tracking code.
                            Once the label is created the field above will auto populate with the tracking code assigned to this shipment.
                            you will then be able to download the label by clicking the button below "Download Label"
                            (Will be visible once the label is created).<Br />
                            You will still have to Submit this from to mark this shipment as "Mailed".
                        </div>
                        <button class="btn btn-primary btn-xs create-label create-label-priority" data-id="{{$row->id}}">Create Label (Priority)</button>
                        &nbsp; <button class="btn btn-danger btn-xs create-label create-label-express" data-id="{{$row->id}}">Create Label (Express)</button>
                        &nbsp; <a class="btn btn-success btn-xs create-label download-label {{!is_null($hasLabel) && isset($hasLabel->id) ? '' : 'hidden'}}"
                                href='{{route("admin.post-completion-pipelines.mail-pipeline.download-label")}}?orderId={{$order->id}}&orderType={{$row->type}}&force-download=1'
                                target='_blank'>Download Latest Label
                            </a>
                    </div>
                </div>
                <div class="col-md-5">
                    <b>Documents Sent</b><br />
                    <table class="table table-striped table-hover">
                        <tr>
                            <th style="width:1px;">&nbsp;</th>
                            <th>Date Uploaded</th>
                            <th>Title</th>
                        </tr>
                        @if($documents)
                            @foreach($documents as $document)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="file_{{$document->id}}" id="file_{{$document->id}}" value="{{$document->id}}" class="file_checkbox">
                                    </td>
                                    <td>{{date('m/d/Y H:i', time($document->created_at))}}</td>
                                    <td>
                                        <a href='/admin/order.php?action=document-download&id={{$order->id}}&fileId={{$document->id}}' rel='tooltip_download' title='Download File'>
                                            {{getDocumentVaultDocumentTypeByRecord($document) == 'Other' || !getDocumentVaultDocumentTypeByRecord($document) ? $document->docname : getDocumentVaultDocumentTypeByRecord($document)}}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">No Files Found</td>
                            </tr>
                        @endif
                    </table>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="do_mark_sent">Submit</button>
        </div>
    </div>
</div>
