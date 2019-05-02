<?php

namespace App\Models\Management;

use App\Models\BaseModel;

class UserFHALicense extends BaseModel
{
    protected $table = 'user_fha_state_approved';

    protected $fillable = [
        'user_id',
        'fha_id',
        'state',
        'expiration',
        'license_number',
        'license_type'
    ];
}
