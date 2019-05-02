@if (isset($clientId))
{{ Form::open(	[ 'route' => ['admin.autoselect.pricing.versions.pricing-update-client', $clientId, $state], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@else
{{ Form::open(	[ 'route' => ['admin.autoselect.pricing.versions.pricing-update-state', $pricing->id, $state], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@endif
<div style="margin-top: 50px;">
	<table class="table table-striped table-hover responsive">
		<tr>
			<th>Appraisal Type</th>
			@foreach($loanTypes as $loanType)
				<th>	
					{{ Form::select('copyfrom['.$loanType->id.']', $loanTypes->pluck('descrip', 'id'), null,  ['class' => 'form-control copy-from', 'data-id' => $loanType->id]) }}	
				</th>
			@endforeach
		</tr>
		<tr>
			<th>&nbsp;</th>
			@foreach($loanTypes as $loanType)
				<th>{{ $loanType->descrip }}</th>
			@endforeach
		</tr>
		
		@foreach($apprTypes as $appraisalTypeId => $appraisalTypeTitle)
				<tr>
					<td>{{ $appraisalTypeTitle }} </td>
					@foreach($loanTypes as $loanType)
						<td>
							<div class="row">
								<div class="col-md-10 loan-type-record-{{ $loanType->id}}">
										{{ Form::text('prices['.$appraisalTypeId.']['.$loanType->id.']', isset($selectedAmounts[$appraisalTypeId][$loanType->id]) ? $selectedAmounts[$appraisalTypeId][$loanType->id] : null, array('class' => ('form-control price-value appr-type-id-' . $appraisalTypeId), 'data-appr-id' => $appraisalTypeId, 'tabindex' => '')) }} 
								
									</div>									
								</div>
							</div>
						</td>
					@endforeach
				</tr>
		@endforeach
	</table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" > Save </button>
</div>
{{Form::close()}}
