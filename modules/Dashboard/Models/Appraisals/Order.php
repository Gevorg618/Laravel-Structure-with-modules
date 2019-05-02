<?php

namespace Dashboard\Models\Appraisals;

use App\Models\Appraisal\Order as BaseOrder;
use App\Scopes\Appraisals\OrdersScope;
use App\Scopes\Appraisals\TempScope;

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

        // static::addGlobalScope(new OrdersScope);
        static::addGlobalScope(new TempScope);
    }
}