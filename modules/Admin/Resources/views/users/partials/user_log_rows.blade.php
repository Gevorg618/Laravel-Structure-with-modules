<table class="table table-responsive">
    <tr>
        <th>Created Date</th>
        <th>Order</th>
        <th>Log Entry</th>
    </tr>

    @if($logs && count($logs))

    @foreach($logs as $log)
    <tr>
        <td>{{ date('m/d/Y H:i', strtotime($log->dts)) }}</td>
        <td><a href='/admin/order.php?id={{ $log->order->id }}' target='_blank'>{{ $log->order->address }}</a></td>
        <td>{{ \Modules\Admin\Helpers\StringHelper::encode($log->info) }}</td>
    </tr>
    @endforeach

    @else
    <tr>
        <td colspan="3">No Logs Found.</td>
    </tr>
    @endif
</table>

{{ $logs->links() }}