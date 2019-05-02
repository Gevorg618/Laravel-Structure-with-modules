<?php

namespace Dashboard\Models\AVM;

use App\Models\AVM\Order as BaseOrder;
use App\Scopes\AVM\OrdersScope;

class Order extends BaseOrder
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OrdersScope);
    }
}