<?php

namespace Modules\Admin\Repositories;


use App\Models\Appraisal\OrderLog;

class OrderLogRepository
{
    /**
     * @param $orderId
     * @param string $info
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getByOrderIdAndInfo($orderId, $info = '')
    {
        return OrderLog::where('orderid', $orderId)
            ->where('info', 'like', $info)
            ->orderBy('dts')->get();
    }

    public function getUserOrderLogsWithLimit($userId, $limit=10)
    {
        return OrderLog::where('userid', $userId)
            ->orderBy('dts')->paginate($limit);
    }
}