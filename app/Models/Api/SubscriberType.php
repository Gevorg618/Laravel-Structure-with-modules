<?php

namespace App\Models\Api;

use App\Models\BaseModel;

class SubscriberType extends BaseModel
{
    protected $table = 'api_subscriber_type';

    protected $fillable = [
        'subscriber_id',
        'type',
    ];

    public $timestamps = false;
}
