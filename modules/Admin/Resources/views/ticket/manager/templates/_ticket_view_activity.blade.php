<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table table-hover table-condensed">
				<tr>
					<th>Activity</th>
					<th>Created Date</th>
					<th>Created By</th>
				</tr>

				@if ($activity->count())
					@foreach ($activity as $r)
						<tr>
							<td>{!! $r->message !!}</td>
							<td>{{ date('m/d/Y g:i A', $r->created_date) }}</td>
							<td>{{ $r->user->fullname }}</td>
						</tr>
					@endforeach
				@else
					<tr>
						<td colspan="4"><i>None Found</i></td>
					</tr>
				@endif
			</table>
		</div>
	</div>
</div>