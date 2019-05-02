@if ($row->closed_date)
	<span class="label label-success">{{ $row->closedFormatDate }}</span>
@else
	<span class="time-show-since label"
		  data-time="{{ $row->createdFormatDate }}">{{ $row->createdFormatDate }}</span>
@endif