@if ($row->has_files)
	<i class="fa fa-paperclip"></i>
@endif

@if ($row->locked_by)
	<a href="javascript:;" rel="tooltip"
	   title="{{ sprintf("Locked By %s On %s", $row->locked, $row->lockedFormatDate) }}">
		<i class="fa fa-eye"></i>
	</a>
@endif