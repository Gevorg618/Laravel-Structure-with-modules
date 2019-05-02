<p class="text-info">Orders Accepted: {{ $totalOrders }} | Orders Completed: {{ $service->getApprOrdersCompletedByUserId($user->id) }}</p>

<div id="user_appr_accepeted_orders">
    @include('users.partials.user_appr_order_rows')
</div>

{{ $orders->links() }}
