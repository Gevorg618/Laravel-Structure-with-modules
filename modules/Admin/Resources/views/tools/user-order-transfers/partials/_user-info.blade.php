
<hr/>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-warning">
			<div class="panel-heading">
				<h3 class="panel-title">Found {{ $ordersCount }} Orders to transfer</h3>
			</div>
			<div class="panel-body">
				<p>You are about to transfer {{ $ordersCount }} orders from
					<b>
						{{ $fromUser->userData->firstname }} {{ $fromUser->userData->lastname }}
					</b>
					 to 
					<b>
						{{ $toUser->userData->firstname }} {{ $toUser->userData->lastname }}
					</b>.
				</p>
				<p>If you wish to continue with the transfer please click the 'Process Order Transfer'.</p>
			</div>
		</div>
	</div>
</div>

<hr/>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-danger">
			<div class="panel-heading">
				<h3 class="panel-title">Orders to transfer</h3>
			</div>
			<div class="panel-body">
				<p>Once the 'Process Order Transer' button is clicked there is no way of un-doing what was done. You'll have to either transfer the orders back or manualy go into each one and transfer it. Once the transfer is complete, you'll be able to see which orders transfered by accessing the record saved under 'Previous Transactions'.</p>
				{{ Form::open([ 'route' => 'admin.tools.user-order-transfers.transfer-order'])}}
					<input type="hidden" name="from_user" value="{{$fromUser->id}}">
					<input type="hidden" name="to_user" value="{{$toUser->id}}">
					<input type="hidden" name="to_type" value="{{ $toType }}">
					<input type="hidden" name="from_type" value="{{ $fromType }}">
					<div class="form-group col-md-12">
	                    <div class="col-lg-12 col-xs-12">
	                        <button type="submit" class="btn btn-danger pull-right">Process Order Transfer</button>
	                    </div>
	                </div>
                {{ Form::close() }}
			</div>
		</div>
	</div>
</div>