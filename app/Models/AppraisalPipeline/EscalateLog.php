<?php

namespace App\Models\AppraisalPipeline;

use App\Models\BaseModel;

class EscalateLog extends BaseModel
{

    protected $table = 'escalate_log';

    public $timestamps = false;

    public static function getContentById($id)
    {
        return self::select('content')->where('orderid', $id)->first();
    }
}
