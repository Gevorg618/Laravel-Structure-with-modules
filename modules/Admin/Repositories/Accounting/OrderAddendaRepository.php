<?php

namespace Modules\Admin\Repositories\Accounting;


use App\Models\Appraisal\OrderAddenda;

/**
 * Class OrderAddendaRepository
 * @package Modules\Admin\Repositories
 */
class OrderAddendaRepository
{
    /**
     * @param $orderId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getOrderInvestAddendas($orderId) {
        return OrderAddenda::where('order_id', $orderId)
            ->whereHas('addenda', function ($query)
            {
                return $query->where('invest', 'Y');
            })->get();
    }
}