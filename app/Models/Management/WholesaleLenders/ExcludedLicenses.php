<?php

namespace App\Models\Management\WholesaleLenders;

use App\Models\BaseModel;

class ExcludedLicenses extends BaseModel
{
    protected $table = 'lenders_excluded_user';

    protected $fillable = [
        'lender_id',
        'firstname',
        'lastname',
        'email',
        'state',
        'zip',
        'license_state',
        'license_number',
    ];

    public $timestamps = false;
}
