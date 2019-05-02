<?php

namespace Modules\Admin\Repositories;


use App\Models\ApprQc;

class ApprQcRepository
{
    public function getNoSentByOrderId($orderId)
    {
        return ApprQc::where('order_id', $orderId)
            ->where('is_sent', 0)->get();
    }
}