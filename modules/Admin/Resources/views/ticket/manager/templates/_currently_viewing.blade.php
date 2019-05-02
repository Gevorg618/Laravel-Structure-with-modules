@foreach ($viewing as $user)
	@if ($user->user_id)
		<dd>{{ $user->fullname }} <small>({{ date('H:i', $user->last_click) }})</small></dd>
	@endif
@endforeach