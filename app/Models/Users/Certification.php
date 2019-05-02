<?php

namespace App\Models\Users;

use App\Models\BaseModel;
use App\Models\Users\User;

class Certification extends BaseModel
{
    protected $table = 'user_certification';

    protected $fillable = [
        'user_id',
        'state',
        'cert_num',
        'cert_expire',
        'license_type'
    ];

    public function scopeOfUser($query, User $user)
    {
        $query->where('user_id', $user->id);
    }

    public function scopeOfState($query, $state)
    {
        $query->where('state', $state);
    }
}
