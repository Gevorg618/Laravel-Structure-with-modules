<table class="table table-striped table-bordered table-hover">
	<thead>
		<th>Order</th>
		<th>Start Time</th>
		<th># Conditions</th>
	</thead>
	<tbody>
        @if($rows && count($rows))
            @foreach($rows as $row)
                <tr class='appr_order_show' id='appr_order_{{$row->order_id}}'>
                    <td>{{ $row->address }}</td>
                    <td>{{date('m/d/Y H:i', $row->created_date)}}</td>
                    <td>{{$row->total}}</td>
                </tr>
            @endforeach
        @endif
	</tbody>
</table>
