@if(isset($clientId))
<div class="btn-group" data-toggle="buttons">
	@foreach($states as $state)
	    <label class="btn btn-default form-check-label state_request" 
	    	data-url='{{ route('admin.autoselect.pricing.versions.pricing-view-by-one-client', [$clientId, $state->abbr]) }}' style="margin-left: 3px; margin-top: 3px">
	        <input class="form-check-input " type="radio" checked autocomplete="off">{{ $state->abbr }}
	    </label>
    @endforeach
</div>
@else
<div class="btn-group" data-toggle="buttons">
	@foreach($states as $state)
	    <label class="btn btn-default form-check-label state_request" 
	    	data-url='{{ route('admin.autoselect.pricing.versions.pricing-view-by-one-state', [$pricing->id, $state->abbr]) }}' style="margin-left: 3px; margin-top: 3px">
	        <input class="form-check-input " type="radio" checked autocomplete="off">{{ $state->abbr }}
	    </label>
    @endforeach
</div>
@endif
