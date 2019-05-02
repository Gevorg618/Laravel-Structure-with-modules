<div class="container">
    <h4>Found {{ $orders ? count($orders) : 0}} Orders</h4>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Client</th>
            <th>Appraiser</th>
            <th>Address</th>
            <th>Date Placed</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody class="borderless">
        @if($orders && count($orders))
            @foreach($orders as $order)
                <tr>
                    <td>{{getUserFullNameById($order->orderedby)}}</td>
                    <td>
                        @if($order->is_assigned)
                            {{$order->is_assigned}}
                        @else
                            {{'N/A'}}
                        @endif
                    </td>
                    <td>{{$order->address}}</td>
                    <td>{{date('m/d/Y g:i A', strtotime($order->ordereddate))}}</td>
                    <td>{{$order->getStatusNameAttribute()}}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td>No Notes Found.</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endif
        </tbody>
    </table>
</div>


