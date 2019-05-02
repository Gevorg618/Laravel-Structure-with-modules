<div class="row">
	<div class="col-md-12">
		<p class="text-muted">Posted By {{ admin()->fullname }} On {{ date('m/d/Y G:i A') }}</p>
	</div>
	
	<div class="col-md-12">
		<blockquote>{!! $comment !!}</blockquote>
	</div>

	<div class="col-md-12">
		<a href="{{ route('admin.ticket.manager.view', ['id' => $ticket->id]) }}"
		   target="_blank">View Ticket [#{{ $ticket->id }}]</a>
	</div>
</div>