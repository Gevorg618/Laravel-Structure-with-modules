<?php

namespace App\Models\Management;

use App\Models\BaseModel;
use App\Models\Users\User;

class FHALicense extends BaseModel
{
    protected $table = 'user_fha_license';

    protected $fillable = [
        'firstname',
        'lastname',
        'middlename',
        'address',
        'city',
        'state',
        'zip',
        'company',
        'license_number',
        'license_type',
        'expiration',
        'pos_lat',
        'pos_long'
    ];

    protected $dates = ['expiration'];

    public function scopeOfUser($query, User $user)
    {
        $query->where(function ($query) use($user) {
            $query->whereRaw("zip = ?", [strtolower(substr($user->userData->comp_zip, 0, 5))])
                    ->whereRaw("LOWER(firstname) = ?", [strtolower($user->userData->firstname)])
                    ->whereRaw("LOWER(lastname) = ?", [strtolower($user->userData->lastname)]);
        })->orWhere(function($query) use($user) {
            $query->whereRaw("LOWER(state) = ?", [strtolower($user->userData->comp_state)])
                    ->whereRaw("LOWER(firstname) = ?", [strtolower($user->userData->firstname)])
                    ->whereRaw("LOWER(lastname) = ?", [strtolower($user->userData->lastname)]);
        });
    }

    public function scopeOfState($query, $state)
    {
        $query->where('state', $state);
    }

    public function scopeofLicenseNumbers($query, $licenses)
    {
        if(count($licenses)) {
            $query->orWhereIn('license_number', $licenses);
        }
    }
}
