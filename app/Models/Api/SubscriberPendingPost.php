<?php

namespace App\Models\Api;

use App\Models\BaseModel;

class SubscriberPendingPost extends BaseModel
{
    protected $table = 'api_subscriber_pending_post';
    protected $fillable = [
        'subscriber_id',
        'rel_id',
        'created_date',
    ];

    public $timestamps = false;
}
