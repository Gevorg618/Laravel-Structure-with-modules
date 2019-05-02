<?php

namespace App\Models\Management;

use App\Models\BaseModel;

class UserASCLicense extends BaseModel
{
    protected $table = 'user_asc_license';

    protected $fillable = [
        'user_id',
        'asc_id',
        'state',
        'expiration',
        'license_number',
        'license_type'
    ];
}
