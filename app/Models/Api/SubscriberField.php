<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class SubscriberField extends Model
{
    protected $table = 'api_subscriber_field';

    protected $fillable = [
        'subscriber_id',
        'field',
    ];

    public $timestamps = false;
}
