<?php

namespace App\Models\Customizations;

use App\Models\BaseModel;

class AMCLicense extends BaseModel
{
    protected $table = 'amc_registration';

    protected $fillable = ['state','reg_number','expires','sec_expires','admin_id'];


    public static function getAMCRegistrationNumber($state)
    {
        return self::where('state', $state)->first();
    }
}
