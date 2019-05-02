@if (!empty($addLink))
    @php $value = (!empty($ticketStatuses)) ? $ticketStatuses[0]->id : 0; @endphp

    <a href="javascript:;" class="inline-status-edit"
       data-pk="{{ $row->id }}" data-value="{{ $value }}" id="status_{{ $row->id }}">
@endif

	@if (!empty($ticketStatuses))
		@foreach ($ticketStatuses as $row)
			@include('admin::ticket.manager.partials._ticket_status_category', ['row' => $row])
		@endforeach
	@else
		<i>{{ config('constants.not_available') }}</i>
	@endif

@if (!empty($addLink))
    </a>
@endif