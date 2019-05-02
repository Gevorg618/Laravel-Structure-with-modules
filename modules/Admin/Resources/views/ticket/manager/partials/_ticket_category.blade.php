@if (!empty($addLink))
    @php $value = (isset($ticketCategories) && $ticketCategories->isNotEmpty()) ? $ticketCategories[0]->id : 0; @endphp

    <a href="javascript:;" class="inline-category-edit"
       data-pk="{{ $ticket->id }}" data-value="{{ $value }}" id="category_{{ $ticket->id }}">
@endif

	@if (!empty($ticketCategories))
		@foreach ($ticketCategories as $r)
			@include('admin::ticket.manager.partials._ticket_status_category', ['row' => $r])
		@endforeach
	@else
		<i>{{ config('constants.not_available') }}</i>
	@endif

@if (!empty($addLink))
    </a>
@endif
