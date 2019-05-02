<br />

<div class="col-md-12">
	<div class="pull-left"></div>
	<div class="pull-right">
		<a href='{{route('admin.integrations.equity-edge-create')}}' class="btn btn-lg btn-primary">Add Record</a>
	</div>
</div>

<br />
<br />
<br />

<table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
               <th>Product</th>
				<th>Appraisal Type</th>
				<th>Loan Reason</th>
				<th>Loan Type</th>
				<th>Options</th>
            </tr>
        </thead>
		@foreach($mercuryEquityEdges as $mercuryEquityEdge)
		<tr>
			<td>{{ $mercuryEquityEdge->product_name }}</td>
			<td>{{ $mercuryEquityEdge->apprType->getTitleAttribute() }}</td>
			<td>{{ $mercuryEquityEdge->loanReason ? $mercuryEquityEdge->loanReason->descrip : 'N/A' }}</td>
			<td>{{ $mercuryEquityEdge->loanType ? $mercuryEquityEdge->loanType->descrip : 'N/A' }}</td>
			<td>
				<div class="btn-group">
				  <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
				    Options <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu" role="menu">
					  <li class="option">
					  	<a href="{{route('admin.integrations.equity-edge-edit',  $mercuryEquityEdge->id )}}" >Update</a>
					  </li>
					  <li class="divider"></li>
					  <li class="option">
					  	<a href="{{ route('admin.integrations.equity-edge-destroy', $mercuryEquityEdge->id )}} " class="delete-confirm">Delete</a>
					  </li>
				  </ul>
				</div>
			</td>
		</tr>
		@endforeach
</table>


