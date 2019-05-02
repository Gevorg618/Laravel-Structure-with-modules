<?php

namespace App\Models\Statistics;

use App\Models\BaseModel;

class UserLog extends BaseModel
{
    protected $table = 'order_log';

    protected $fillable = [
        'orderid',
        'dts',
        'userid',
        'info',
        'ticketid',
        'is_highlight',
        'email',
        'html_content',
        'type_id',
        'is_client_visible',
        'is_appr_visible',
    ];

    public $timestamps = false;

    public function scopeFilter($query, $filter)
    {
        if (!empty($filter['date_from'])) {
            $query->where('dts', '>=', $filter['date_from']);
        }
        if (!empty($filter['date_to'])) {
            $query->where('dts', '<=', $filter['date_to']);
        }
        if (!empty($filter['admin'])) {
            $query->where('userid', '=', $filter['admin']);
        }
        if (!empty($filter['log_type'])) {
            $query->where('type_id', '=', $filter['log_type']);
        }
        return $query;
    }

    public function logType()
    {
        return $this->hasOne('App\Models\Appraisal\LogType', 'id', 'type_id');
    }
}
