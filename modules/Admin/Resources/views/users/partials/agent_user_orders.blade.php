<?php


if($agentOrders) {
    foreach($agentOrders as $order) {
        $orders[$order->id] = $order;
    }
}

if($agentSubOrders) {
    foreach($agentSubOrders as $order) {
        $alOrder = $service->getALOrderInfo($order->parent_order_id);
        $orderId = $alOrder->id;
        $merge = array_merge( (array) $alOrder, (array) $r );
        $merge['id'] = $orderId;
        $orders[$order->parent_order_id] = (object) $merge;
    }
}
?>

<p class="text-info">Orders Accepted: {{ number_format(getALOrdersAcceptedByUserId($user->id)) }} | Orders Completed: {{ number_format(getALOrdersCompletedByUserId($user->id)) }}</p>

<div id="agent_orders">
    <table>
        <tr>
            <th>Ordered</th>
            <th>Completed</th>
            <th>Client</th>
            <th>Address</th>
            <th>Status</th>
            <th>Order Type</th>
            <th style="width:100px;">Fees</th>
        </tr>
        @if($orders)

        @foreach($orders as $order)
        <tr>
            <td>{{ date('m/d/Y H:i', strtotime($order->ordereddate)) }}</td>
            <td>{{ $order->submitted ? date('m/d/Y H:i', strtotime($order->submitted)) : '--' }}</td>
            <td>{{ $order->groupName }}</td>
            <td><a href='/admin/alorder.php?id={{ $order->id }}' target='_blank'>{{ $order->address }}</a></td>
            <td>{{ $order->statusName }}</td>
            <td>{{ $service->getALFullProductTypeTitle($order) }}</td>
            <td>
                Inv <strong>{{ number_format($order->invoicedue, 2) }}</strong><br />
                Split <strong>{{ number_format($order->split_amount, 2) }}</strong><br />
                Margin <strong>{{ number_format($order->invoicedue-$order->split_amount, 2) }}</strong><br />
            </td>
        </tr>
        @endforeach

        @else

        <tr>
            <td colspan="10">No Orders Found.</td>
        </tr>

        @endif
    </table>
</div>
