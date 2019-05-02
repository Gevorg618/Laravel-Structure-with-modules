<?php

namespace App\Models\Management;

use App\Models\BaseModel;
use App\Models\Users\User;

class ASCLicense extends BaseModel
{
    protected $table = 'asc_data';
    protected $fillable = [
        'st_abbr',
        'lic_number',
        'lname',
        'fname',
        'mname',
        'name_suffix',
        'street',
        'city',
        'state',
        'zip',
        'company',
        'phone',
        'country',
        'country_code',
        'status',
        'lic_type',
        'exp_date',
        'post_lat',
        'post_long'
    ];

    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            $query->where('fname', 'like', $search . '%');
            $query->orWhere('lname', 'like', $search . '%');
            $query->orWhere('lic_number', 'like', $search . '%');
            $query->orWhere('city', 'like', $search . '%');
        }
        return $query;
    }

    public function scopeFilter($query, $filter)
    {
        if (!empty($filter)) {
            if (!empty($filter['from'])) {
                $query->where('exp_date', '>=', $filter['from']);
            }
            if (!empty($filter['to'])) {
                $query->where('exp_date', '<=', $filter['to']);
            }
            if (!empty($filter['state'])) {
                $query->where('state', '=', $filter['state']);
            }
            if (!empty($filter['license_status'])) {
                $query->where('status', '=', $filter['license_status']);
            }
            if (!empty($filter['license_type'])) {
                $query->where('lic_type', '=', $filter['license_type']);
            }
        }
        return $query;
    }

    public function scopeOfUser($query, User $user)
    {
        $query->where(function ($query) use($user) {
            $query->whereRaw("zip = ?", [strtolower(substr($user->userData->comp_zip, 0, 5))])
                    ->whereRaw("LOWER(fname) = ?", [strtolower($user->userData->firstname)])
                    ->whereRaw("LOWER(lname) = ?", [strtolower($user->userData->lastname)]);
        })->orWhere(function($query) use($user) {
            $query->whereRaw("LOWER(state) = ?", [strtolower($user->userData->comp_state)])
                    ->whereRaw("LOWER(fname) = ?", [strtolower($user->userData->firstname)])
                    ->whereRaw("LOWER(lname) = ?", [strtolower($user->userData->lastname)]);
        });
    }

    public function scopeOfState($query, $state)
    {
        $query->where('st_abbr', $state);
    }

    public function scopeofLicenseNumbers($query, $licenses)
    {
        if(count($licenses)) {
            $query->orWhereIn('lic_number', $licenses);
        }
    }

    public function getLicenseTypeAttribute()
    {
        return static::getLicenseTypes()[$this->lic_type] ?? null;
    }

    public function getStatusTitleAttribute()
    {
        return static::getLicenseStatus()[$this->status] ?? null;
    }

    public static function getLicenseTypes()
    {
        return [
            1 => 'Licensed',
            2 => 'Certified General',
            3 => 'Certified Residential',
            4 => 'Transitional License',
        ];
    }

    public static function getLicenseStatus()
    {
        return [
            1 => 'Active',
            0 => 'Inactive'
        ];
    }
}
