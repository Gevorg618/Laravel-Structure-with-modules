@foreach($orders as $order)
    <tr>
        <td>{{ $order->id }}</td>
        <td class="text-lowercase">{{$order->propaddress1 }} {{ $order->propaddress2}} {{ $order->propcity}} , {{$order->propstate}}</td>
        <td>{{ $order->borrower }}</td>
    </tr>
@endforeach