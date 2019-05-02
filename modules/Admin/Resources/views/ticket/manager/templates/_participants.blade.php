@if ($participants)
	@foreach ($participants as $row)
		<dd style="margin-bottom:2px;">
			<button class="btn btn-xs btn-danger remove-participant-user"
					data-id="{{ $row->id }}"><i class="fa fa-times"></i> </button> {{ $row->fullname }}
		</dd>
	@endforeach
@else
	<dd><i>{{ config('constants.not_available') }}</i></dd>
@endif