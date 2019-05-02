<?php

namespace Modules\Admin\Repositories;


use App\Models\AlternativeValuation\OrderProductRelation;

class AltOrderProductRelationRepository
{
    /**
     * @param $orderId
     * @param $productId
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getByOrderAndProduct($orderId, $productId)
    {
        return OrderProductRelation::where('order_id', $orderId)
            ->where('product_id', $productId)->first();
    }
}
