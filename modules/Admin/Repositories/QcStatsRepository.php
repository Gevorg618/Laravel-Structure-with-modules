<?php

namespace Modules\Admin\Repositories;


use App\Models\Appraisal\QC\Stat;

class QcStatsRepository
{
    /**
     * @param $orderId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getSentBack($orderId ,$limit = 2)
    {
        return Stat::where('order_id', $orderId)
            ->where('sent_back', 1)
            ->orderBy('id')
            ->limit($limit)->get();
    }
}