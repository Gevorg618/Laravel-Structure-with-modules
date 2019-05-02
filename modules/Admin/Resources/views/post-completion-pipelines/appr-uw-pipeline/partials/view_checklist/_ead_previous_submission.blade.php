<table class="table tabl-hover table-striped table-condensed">
	<tr>
		<th>EAD ID</th>
		<th>Submission Date</th>
		<th>Submitted By</th>
	</tr>
	@if($orderEADSubmission)
		<tr>
			<td>{{$orderEADSubmission->doc_file_id}}</td>
			<td>{{date('m/d/Y g:i A', $orderEADSubmission->created_date)}}</td>
			<td>{{getUserFullNameById($orderEADSubmission->created_by)}}</td>
		</tr>
	@else
		<tr>
			<td colspan="5">No Submissions</td>
		</tr>
	@endif
</table>

@if($orderUCDPSubmission)

	@if($orderUCDPSubmission->last_updated_date)
		<p class="text-danger">Last Updated on <i>{{date('m/d/Y g:i A', $orderUCDPSubmission->last_updated_date)}}</i> By <i>{{getUserFullNameById($orderUCDPSubmission->last_updated_by)}}</i></p>
	@endif

	@if($FNMDocument)
		<a href="#" class="btn btn-md btn-success"><i class="fa fa-cloud-download"></i> FNM SSR</a>
	@endif
@endif