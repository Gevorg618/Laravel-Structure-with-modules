<?php

namespace Modules\Admin\Repositories\Appraisal;


use App\Models\Appraisal\OrderDelayCode;

class OrderDelayCodeRepository
{
    /**
     * @param $orderId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getDelayCodes($orderId)
    {
        return OrderDelayCode::where('order_id', $orderId)
            ->where('start_date', '>', 0)
            ->where('end_date', '>', 0)
            ->get();
    }
}