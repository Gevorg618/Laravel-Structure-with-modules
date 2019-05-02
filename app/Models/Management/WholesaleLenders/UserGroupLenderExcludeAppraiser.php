<?php

namespace App\Models\Management\WholesaleLenders;

use App\Models\BaseModel;

class UserGroupLenderExcludeAppraiser extends BaseModel
{
    protected $table = 'user_group_lender_exclude_appraiser';

    protected $fillable = [
        'lenderid',
        'userid',
        'created_date',
    ];

    public $timestamps = false;
}
