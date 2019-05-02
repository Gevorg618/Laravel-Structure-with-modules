<div class="row">
	<div class="col-md-12">
		<form class="form-horizontal" role="form">
		  <div class="form-group">
		    <label class="col-sm-4 control-label">To Address</label>
		    <div class="col-sm-8">
		      <p class="form-control-static">{{ $row->to_address }}</p>
		    </div>
		  </div>

		  @if ($row->additional_addresses)
		  	<div class="form-group">
		    <label class="col-sm-4 control-label">Additional Addresses</label>
		    <div class="col-sm-8">
		      <p class="form-control-static">{{ $row->additional_addresses }}</p>
		    </div>
		  </div>
		  @endif

		  @if ($row->attachments)
		  	<div class="form-group">
		    <label class="col-sm-4 control-label">Attachments</label>
		    <div class="col-sm-8">
		      <p class="form-control-static">{!! implode('<br>', explode(',', $row->attachments)) !!}</p>
		    </div>
		  </div>
		  @endif
		</form>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		{!! $row->html_content !!}
	</div>
</div>