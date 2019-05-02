<?php

namespace App\Models\Management\WholesaleLenders;

use App\Models\BaseModel;

class UserGroupLenderRel extends BaseModel
{
    protected $table = 'user_group_lender_rel';

    protected $fillable = [
        'lenderid',
        'groupid'
    ];

    public $timestamps = false;
}
