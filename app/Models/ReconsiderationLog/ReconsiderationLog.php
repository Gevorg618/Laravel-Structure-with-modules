<?php

namespace App\Models\ReconsiderationLog;

use App\Models\BaseModel;

class ReconsiderationLog extends BaseModel
{
    protected $table = 'reconsideration_log';

    public static function getLogCount($orderId, $orderRevision)
    {
        return self::where('orderid', $orderId)->where('revision', $orderRevision)->count();
    }
}
