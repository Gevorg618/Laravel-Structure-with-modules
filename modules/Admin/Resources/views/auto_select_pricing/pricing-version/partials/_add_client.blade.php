<?php $count = 0;
$stateColumn = 8;
$headerRow = 14; ?>
{{ Form::open(	[ 'route' => ['admin.autoselect.pricing.versions.pricing-store-client', $pricing->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
	<div class="form-group col-md-12">
	    <label name="loan_reason" class="control-label col-lg-3 col-xs-12">Clients
	    </label>
    	<div class="col-lg-12 col-xs-12">
			{{ Form::select('client', $clients, null,  ['class' => 'form-control',]) }}
		</div>
	</div>


	<div>
		<span style="color:#a7cade">Conventional</span> |
		<span style="color:#7AA823">FHA</span>
	</div>
	<div style="margin-top: 50px;" class="table-responsive">
	<table class="table table-striped table-hover responsive">
		<tr>
			<th class="state_th">State</th>
			<?php $stateCount = 0 ?>
			@foreach($apprTypes as $typeId => $typeName)
				@if($stateCount == $stateColumn)
					<th class="state_th">State</th>
					<?php $stateCount = 0 ?>
				@endif
				
				<th>{{ $typeName }}</th>
				<?php $stateCount++; ?>
			@endforeach 
		</tr>

		<?php 	$totalRowCount = count($apprTypes) * count($states); ?>
		<?php	$currentColumnIndex = 0; ?>
		<?php	$currentRowIndex = 1; ?>
		

		<?php $tabIndex = 0; ?>
		@foreach($states as $state)
			<tr class="highlight-row">
				<td>{{ $state->abbr}}</td>
				<?php $stateCount = 0; $currentRowIndex = 1; ?>

				@foreach($apprTypes as $typeId => $typeName)
					@if($stateCount == $stateColumn)
						<td>{{ $state->abbr}}</td>
						<?php $stateCount = 0; ?>
					@endif
					
					<td>
						<?php 
							$tabIndex = $currentRowIndex++;
							$tabIndexFHA = $currentRowIndex++;
						?>
						{{ Form::text('prices['.$state->abbr.']['.$typeId.'][amount]', !empty($values[$state->abbr][$typeId]['amount']) ?	$values[$state->abbr][$typeId]['amount']  : '0.00', array('class' => ('form-control price-value'))) }} 

						{{ Form::text('prices['.$state->abbr.']['.$typeId.'][fha_amount]', !empty($values[$state->abbr][$typeId]['fha_amount']) ? $values[$state->abbr][$typeId]['fha_amount'] : '0.00',  array('class' => ('form-control amount-field'))) }} 
					</td>
					
					<?php $stateCount++; ?>
				@endforeach
			</tr>
			
			<?php $count++; ?>
			@if($count == $headerRow)
				<tr>
					<th class="state_th">State</th>
					<?php $stateCount = 0; ?>
					@foreach($apprTypes as $typeId => $typeName)
						@if($stateCount == $stateColumn)
							<th >State</th>
							<?php $stateCount = 0; ?>
						@endif

						<th>{{ $typeName}}</th>
						<?php $stateCount++; ?>
					@endforeach
				</tr>
				<?php $count = 0; ?>
			@endif	
			
		@endforeach
		
		<tr>
			<th >State</th>
			<?php $stateCount = 0; ?>
			@foreach($apprTypes as $typeId => $typeName)
				@if($stateCount == $stateColumn)
					<th >State</th>
					<?php $stateCount = 0; ?>
				@endif
				
				<th>{{ $typeName}}</th>
				<?php $stateCount++; ?>
			@endforeach
		</tr>
	</table>

<div class="clear"></div>


	<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" > Save </button>
</di
{{Form::close()}}

<style>
.grid_16 td {
	text-align: center;
}

#pricing_div_data {
	overflow-x: scroll;
}

#pricing_div_table {
	min-width: 3600px;
}

#pricing_div_table th {
	width: 90px;
}

#pricing_div_table th.state_th {
	width: 30px;
}

#states_list td {
	background-color: #fff;
}

#states_list td:even {
	background-color: #F0F0F2;
}

.is-highlight td, .is-highlight-clicked td {
	background-color: #a7cade !important;
}

.prices-input {
	width: 40px;
	float: none;
}

.amount-field {
	border: 1px solid #a7cade;
}

.fhaamount-field {
	border: 1px solid #7AA823;
}

</style>