<table class="table table-responsive">
    <tr>
        <th>Ordered</th>
        <th>Completed</th>
        <th>TT</th>
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
        <td>{{ $order->date_delivered ? date('m/d/Y H:i', strtotime($order->date_delivered)) : '--' }}</td>

        <?php
        $adjustedTurnTime = $service->getOrderAdjustedTurnTime($order);

        $dateDeliveredUnix = $service->getOrderDateDeliveredTimeStamp($order);
        $turnTime = $service->getOrderTurnTimeByDateDelivered($order, $dateDeliveredUnix);
        $turnTime = $service->getOrderTurnTimeInMinutesByTurnTimeString($turnTime);
        ?>

        <td>{{ $adjustedTurnTime ? $adjustedTurnTime : $turnTime }}</td>
        <td>{{ $order->groupName }}</td>
        <td><a href='/admin/order.php?id={{ $order->id }}' target="_blank">{{ $order->address }}</a></td>
        <td>{{ $order->statusName }}</td>
        <td>{{ $order->apprTypeName }}</td>
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