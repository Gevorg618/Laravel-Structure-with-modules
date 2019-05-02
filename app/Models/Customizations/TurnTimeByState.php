<?php

namespace App\Models\Customizations;

use App\Models\BaseModel;

class TurnTimeByState extends BaseModel
{
    protected $table = 'turntime_by_state';

    protected $fillable = [
        'state',
        'days'
    ];

    public $timestamps = false;
}
