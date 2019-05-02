<?php

namespace Modules\Admin\Repositories;


use App\Models\ApprUw;

class ApprUwRepository
{
    /**
     * @param $orderId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getByOrder($orderId)
    {
        return ApprUw::where('order_id', $orderId)->get();
    }
}
