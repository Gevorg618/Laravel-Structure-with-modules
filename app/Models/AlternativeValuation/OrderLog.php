<?php

namespace App\Models\AlternativeValuation;

use App\Models\BaseModel;

class OrderLog extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alt_order_log';

    protected $fillable = [
        'order_id',
        'sub_order_id',
        'client_visible',
        'agent_visible',
        'type_id',
        'message',
        'userid',
        'dts',
        'html_content',
    ];

    public $timestamps = false;
}
