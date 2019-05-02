<h2>Payments Sent</h2>
<a href='/admin/pay_appr.php?userid={{ $user->id }}' target='_blank'>Send Payments To User</a>
<table>
    <tr>
        <th>Ordered</th>
        <th>Completed</th>
        <th>Paid Date</th>
        <th>Payment Sent</th>
        <th>Address</th>
        <th>Payment Type</th>
        <th>Check Number</th>
        <th>Check Amount</th>
        <th>Split Amount</th>
    </tr>

    @if($payments)
    @foreach($payments as $payment)
    @if($user->user_type == 14)
    @php
    $order = $service->getALOrderInfo($payment->orderid);
    $sub = $service->getAgentByIdSubOrder($order->id, $user->id);
    if($sub) {
        $order->agent_paid = $sub->agent_paid;
    }
    @endphp
    <tr>
        <td>{{ date('m/d/Y H:i', strtotime($order->ordereddate)) }}</td>
        <td>{{ $order->submitted ? date('m/d/Y H:i', strtotime($order->submitted)) : '--' }}</td>
        <td>{{ $order->agent_paid ? date('m/d/Y H:i', strtotime($order->agent_paid)) : '--' }}</td>
        <td>{{ date('m/d/Y', strtotime($payment->date_sent)) }}</td>
        <td><a href='/admin/alorder.php?id={{ $order->id }}' target='_blank'>{{ $order->address }}</a></td>
        <td>{{ ucwords(strtolower($payment->paidby)) }}</td>
        <td>{{ $payment->checknum }}</td>
        <td>${{ number_format($payment->checkamount) }}</td>
    </tr>
    @else
    @php $order = $service->getApprOrderById($payment->orderid); @endphp
    <tr>
        <td>{{ date('m/d/Y H:i', strtotime($order->ordereddate)) }}</td>
        <td>{{ $order->date_delivered ? date('m/d/Y H:i', strtotime($order->date_delivered)) : '--' }}</td>
        <td>{{ date('m/d/Y H:i', strtotime($payment->paid)) }}</td>
        <td>{{ date('m/d/Y', strtotime($payment->date_sent)) }}</td>
        <td><a href='/admin/order.php?id={{ $order->id }}' target='_blank'>{{ $order->address }}</a></td>
        <td>{{ ucwords(strtolower($payment->paidby)) }}</td>
        <td>{{ $payment->checknum }}</td>
        <td>${{ number_format($payment->checkamount) }}</td>
        <td>${{ number_format($order->split_amount) }}</td>
    </tr>
    @endif


    @endforeach
    @else
    <tr>
        <td colspan="10">No Payments Found.</td>
    </tr>
    @endif
</table>
