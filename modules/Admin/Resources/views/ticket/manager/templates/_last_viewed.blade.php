@if ($ticket && $ticket->viewers)
	@foreach ($ticket->viewers as $r)
		<dd>{{ $r->fullname }} <small>({{ date('m/d/y g:i A', $r->created_date) }})</small></dd>
	@endforeach
@endif