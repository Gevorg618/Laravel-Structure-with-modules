@if ($row->orderid)
	@if ($row->type == config('constants.order_type_alt'))
		<a href="/admin/alorder.php?id={{ $row->orderid }}" target="_blank">{{ $row->orderid }}</a>
	@elseif ($row->type == config('constants.order_type_vault'))
		<a href="/docuvaultorder.php?id={{ $row->orderid }}" target="_blank">{{ $row->orderid }}</a>
	@elseif ($row->type == config('constants.order_type_avm'))
		<a href="/avmorder.php?id={{ $row->orderid }}" target="_blank">{{ $row->orderid }}</a>
	@else
		<a href="/admin/order.php?id={{ $row->orderid }}" target="_blank">{{ $row->orderid }}</a>
	@endif
@else
	<span><i>{{ config('constants.not_available') }}</i></span>
@endif