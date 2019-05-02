@if ($users)
	<div class="dropdown open">
	  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
	  	@foreach ($users as $userId => $userName)
	  		<li><a data-id="{{ $userId }}" data-label="{{ $userName }}" href="javascript:;"
				   class="mention-users">{{ $userName }}</a></li>
	  	@endforeach
	  </ul>
	</div>
@endif