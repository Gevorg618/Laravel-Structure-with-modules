<h2>Assignment Invites </h2>

<table>
    <tr>
        <th>Total</th>
        <th>Accepted</th>
        <th>Declined</th>
        <th>% Accepted</th>
        <th>% Declined</th>
    </tr>
    <tr>
        <td>{{ number_format($inviteCounts['total']) }}</td>
        <td>{{ number_format($inviteCounts['accepted']) }}</td>
        <td>{{ number_format($inviteCounts['declined']) }}</td>
        <td>{{ $inviteCounts['total'] ? number_format(($inviteCounts['accepted']/$inviteCounts['total']*100), 2) : 0 }}%</td>
        <td>{{ $inviteCounts['total'] ? number_format(($inviteCounts['declined']/$inviteCounts['total']*100), 2) : 0 }}%</td>
    </tr>
</table>

@if($invites)
<table class="table table-condensed">
    <thead>
    <th>Date Sent</th>
    <th>Property</th>
    <th style="width:1px">Active</th>
    <th style="width:1px">Declined</th>
    <th style="width:1px">Accepted</th>
    <th style="width:1px">Offer</th>
    </thead>
    <tbody>
    @foreach($invites as $invite)
    @php $order = $service->getApprOrderById($invite->order_id); @endphp
    <tr class="order-invite" id='order-invite-{{ $invite->id }}'>
        <td>{{ date('m/d/Y H:i', $invite->created_date) }}</td>
        <td><a href='/admin/order.php?id={{ $order->id }}' target='_blank'>{{ $order->address }}</a></td>

        <td>{{ $service->getOrderDocumentYesNoImage($invite->is_active) }}</td>
        <td>{{ $invite->is_rejected ? 'Y' : 'N' }}</td>
        <td>{{ $invite->is_accepted ? 'Y' : 'N' }}</td>
        <td>
            <a href='/invite.php?code={{ $invite->code }}' rel='tooltip_download' title='View Invite' target='_blank'>
                <img src='/images/icons/famfamfam/zoom.png' alt='View' />
            </a>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
@endif