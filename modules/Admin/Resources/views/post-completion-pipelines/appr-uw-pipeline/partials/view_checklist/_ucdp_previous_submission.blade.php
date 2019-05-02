<table class="table tabl-hover table-striped table-condensed">
	<tr>
		<th>UCDP ID</th>
		<th>FNM</th>
		<th>FRE</th>
		<th>Submission Date</th>
		<th>Submitted By</th>
	</tr>
	@if($orderUCDPSubmission)
		<tr>
			<td>{{$orderUCDPSubmission->doc_file_id}}</td>
			<td>{{$orderUCDPSubmission->fnm_success ? 'Yes' : 'No'}}</td>
			<td>{{$orderUCDPSubmission->fre_success ? 'Yes' : 'No'}}</td>
			<td>{{date('m/d/Y g:i A', $orderUCDPSubmission->created_date)}}</td>
			<td>{{getUserFullNameById($orderUCDPSubmission->created_by)}}</td>
		</tr>
	@else
		<tr>
			<td colspan="5">No Submissions</td>
		</tr>
	@endif
</table>

@if($orderUCDPSubmission && $orderUCDPSubmission->risk_score)
    <div class="alert alert-info">
        <b>Risk Score:</b> {{$orderUCDPSubmission->risk_score}}
    </div>
@endif

@if($orderUCDPSubmission)

	@if($orderUCDPSubmission->last_updated_date)
		<p class="text-danger">Last Updated on <i>{{date('m/d/Y g:i A', $orderUCDPSubmission->last_updated_date)}}</i> By <i>{{getUserFullNameById($orderUCDPSubmission->last_updated_by)}}</i></p>
	@endif

	@if($FNMDocument)
		<a href="#" class="btn btn-md btn-success"><i class="fa fa-cloud-download"></i> FNM SSR</a>
	@endif

	@if($FREDocument)
		<a href="#" class="btn btn-md btn-success"><i class="fa fa-cloud-download"></i> FRE SSR</a>
	@endif
@endif