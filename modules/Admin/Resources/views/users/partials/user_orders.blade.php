<?php

$orders = $service->getUserSubmittedOrderswithLimit($user->id);

$alOrders = $service->getALUserSubmittedOrdersWithLimit($user->id);
?>

<div class="row">
    <div class="span9">
        <div id="userorders" class="smaller-font-size">
            <ul>
                <li><a href="#tabs-1">Appraisals</a></li>
                <li><a href="#tabs-2">MarkIt Value</a></li>
            </ul>
            <div id="tabs-1">
                <p class="text-info"></p>
                <div id="user_appraisal_orders">
                    @include('users.partials.user_appr_order_rows')
                </div>

                <div class="row">
                    <div class="span12">
                        {!! $orders->links() !!}
                    </div>
                </div>
            </div>
            <div id="tabs-2">
                <p class="text-info"></p>
                <div id="users_markitvalue_orders">
                    @include('users.partials.user_al_orders')
                </div>
                <div class="row">
                    <div class="span12">
                        {!! $alOrders->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
