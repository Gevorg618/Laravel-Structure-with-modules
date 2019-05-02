<?php

namespace App\Models\Management\WholesaleLenders;

use App\Models\BaseModel;

class UserGroupLenderStateRel extends BaseModel
{
    protected $table = 'user_group_lender_state_rel';

    protected $fillable = [
        'lenderid',
        'state'
    ];

    public $timestamps = false;
}
