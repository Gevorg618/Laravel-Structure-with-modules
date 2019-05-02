<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="view_mail_record_title">Viewing Order Information</h4>
        </div>
        <div class="modal-body" id="view_mail_record_content">
            <div class="row">
                <div class="col-md-6">
                    <b>Mail To:</b> <Br />
                    {{ $order->final_appraisal_borrower_name }}<Br />
                    {{ $order->final_appraisal_borrower_address1 }}<br />
                    @if($order->final_appraisal_borrower_address2)
                        {{ $order->final_appraisal_borrower_address2 }}<br />
                    @endif
                    {{ $order->final_appraisal_borrower_city}}, {{$order->final_appraisal_borrower_state}} {{$order->final_appraisal_borrower_zip}}
                    @if(!$row->is_ready && !$row->sent_date)
                        <br /><br /><b>Mark Ready To Mail</b> <Br />
                        <button class="btn btn-primary btn-small" id="mark-ready-to-mail" data-id="{{$row->id}}">Ready To Mail</button>
                    @endif
                    @if($row->tracking_number)
                        <div>
                            <b>Tracking Number:</b> <a href='https://tools.usps.com/go/TrackConfirmAction?qtc_tLabels1={{preg_replace('/[^0-9]/', '', $row->tracking_number)}}' target='_blank'>{{$row->tracking_number}}</a>
                        </div>
                    @endif
                    @if($row->sent_date && $row->sent_date < strtotime('-7 days'))
                        <div class="alert alert-warning">
                            <p>Its been over 7 days since this was mailed. You can either mark this manualy as failed/delivered or edit the tracking number.</p>
                            <button class="btn btn-danger btn-small" id="mark-manual-failed" data-id="{{$row->id}}">Failed</button>
                            <button class="btn btn-success btn-small" id="mark-manual-delivered" data-id="{{$row->id}}">Delivered</button>
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    @if($row->type == 'docuvault')
                        <a href='/docuvaultorder.php?id={{$order->id}}' target="_blank">View Order</a><br />
                        @if(is_object($finalAppraisal))
                            <b>Final Report:</b>
                            <a href="/docuvaultorder.php?action=document-download&id={{$order->id}}&fileId={{$finalAppraisal->id}}&inline=1" rel='tooltip' target='_blank' title='Click To View The Final Appraisal Document'>{{$order->propaddress1}}_Appraisal.pdf</a>
                            <img src='/images/page_white_acrobat.png' style='vertical-align:bottom;' alt='Adobe Acrobat (PDF)'/>
                            <a href="/docuvaultorder.php?action=document-download&id={{$order->id}}&fileId={{$finalAppraisal->id}}" rel='tooltip' target='_blank' title='Click To Download The Final Appraisal Document'>
                            <img src='/images/savedisk.png' style='vertical-align:bottom;' alt='Adobe Acrobat (PDF)'/>
                            </a><br />
                        @endif
                        @if($invoice)
                            <b>Invoice:</b>
                            <a href="/docuvaultorder.php?action=document-download&id={{$order->id}}&fileId={{$invoice->id}}&inline=1" target="_blank">Click To View</a><Br />
                        @endif
                        @if($icc)
                            <b>Certification:</b>
                            <a href="/docuvaultorder.php?action=document-download&id={{$order->id}}&fileId={{$icc->id}}&inline=1" target="_blank">Click To View</a><Br />
                        @endif
                        @if($row->sent_date)
                            <b>Confirmation:</b>
                            <a href="/docuvaultorder.php?action=document-vault-confirmation&id={{$order->id}}" target="_blank">Click To View</a><Br />
                        @endif
                        <b>Borrower Letter:</b>
                        <a href="/docs/borrower_letter.pdf" target="_blank">Click To View</a><Br />
                    @elseif($row->type == 'appr')
                        <a href='/admin/order.php?id={{$order->id}}' target="_blank">View Order</a><br />
                        @if(is_object($finalAppraisal))
                            <b>Final Report:</b>
                            <a href="/admin/order.php?action=document-download&id={{$order->id}}&fileId={{$finalAppraisal->id}}&inline=1" rel='tooltip' target='_blank' title='Click To View The Final Appraisal Document'>{{$order->propaddress1}}_Appraisal.pdf</a>
                            <img src='/images/page_white_acrobat.png' style='vertical-align:bottom;' alt='Adobe Acrobat (PDF)'/>
                            <a href="/admin/order.php?action=document-download&id={{$order->id}}&fileId={{$finalAppraisal->id}}" rel='tooltip' target='_blank' title='Click To Download The Final Appraisal Document'>
                            <img src='/images/savedisk.png' style='vertical-align:bottom;' alt='Adobe Acrobat (PDF)'/>
                            </a><br />
                        @endif
                        <b>Invoice:</b>
                        <a href="/printinvoice.php?oid={{$order->id}}" target="_blank">Click To View</a><Br />
                        <b>Landmark Cert:</b>
                        <a href="/icc_cert.php?id={{$order->id}}" target="_blank">Click To View</a><Br />
                        @if($row->sent_date)
                            <b>Confirmation:</b>
                            <a href="/order.php?action=document-vault-confirmation&id={{$order->id}}" target="_blank">Click To View</a><Br />
                        @endif
                        <b>Borrower Letter:</b>
                        <a href="/docs/borrower_letter.pdf" target="_blank">Click To View</a><Br />
                    @endif
                    <table class="table table-striped table-hover">
                        <tr>
                            <th>Date Uploaded</th>
                            <th>Title</th>
                        </tr>
                        @if($documents)
                            @foreach($documents as $document)
                                <tr>
                                    <td>{{date('M/d/Y g:i A', time($document->created_at))}}</td>
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
        </div>
    </div>
</div>
