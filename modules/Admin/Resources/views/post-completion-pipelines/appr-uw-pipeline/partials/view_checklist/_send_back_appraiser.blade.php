<div class="row form-checklist-option-send-back form-option-checklist">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-10 pull-left">
						<h3 class="panel-title">Send Back To Appraiser</h3>
					</div>
					<div class="col-md-2 pull-right">
						<button type="button" class="btn btn-xs btn-primary update-email-contents">Update Email
							Contents
						</button>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="appraiser_email" class="col-md-4 control-label">Appraiser Email</label>
							<div class="col-md-8">
								<input type="text" name="appraiser_email" id="appraiser_email" class="form-control"
									   placeholder="Appraiser Email" value="{{$order->appr_email}}">
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="appraiser_name" class="col-md-4 control-label">Appraiser Name</label>
							<div class="col-md-8">
								<input type="text" name="appraiser_name" id="appraiser_name" class="form-control" placeholder="Appraiser Name"
									   value="{{$order->appr_name }}">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<br/>
						<div class="form-group">
							<label for="appraiser_subject" class="col-md-2 control-label">Subject</label>
							<div class="col-md-10">
								<input type="text" id="appraiser_subject" name="appraiser_subject" class="form-control"
									   placeholder="Appraiser Subject Line"
									   value="QC Corrections - {{$order->id}} - {{ucwords(strtolower($order->propaddress1))}}">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<b>Attachments</b>
					</div>
					<div class="col-md-12">
						@include('admin::appraisal._qc_attachments', ['orderId' => $order->id, 'orderFiles' => $orderFiles,'name' => 'appraiser_attach', 'attacheApprasierFiles' => true])
						<hr/>
					</div>
					<div class="col-md-12">
						<label for="appraiser_email_content">Appraiser email</label>
						<textarea name="appraiser_email_content" id="appraiser_email_content" class="editor">
							{{$appraiserTemplate = convertOrderKeysToValues($apprEmailContent, $order)}}
						</textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@push('scripts')
	<script>
        var $iframe = null;
        var $iframeContents = null;
        var $count = {};
        var $checklist = {};
        var $comments = {};
        var $orderId = '<?php echo intval($order->id); ?>';
        var $qcId = '<?php echo intval($record->id); ?>';
        var $appraiserTemplate = <?php echo json_encode($appraiserTemplate); ?>;
        var $totalTimer = <?php echo intval($totalTime); ?>;
        var $currentTimer = 0;
        var $totalTimerMoment = ($totalTimer * 1000);
        var $currentTimerMoment = 0;
        var $updateEmailClicked = false;
        var $isChecklistClientNotRequired = 2;

        // Auto Update Currently Viewing
        var $autoCheckCurrentlyViewing = true;
        var $autoCheckCurrentlyViewingInterval = 10000;
        var $currentlyViewing = [];
	</script>
@endpush
