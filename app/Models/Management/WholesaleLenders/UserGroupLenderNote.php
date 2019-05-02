<?php

namespace App\Models\Management\WholesaleLenders;

use App\Models\BaseModel;

class UserGroupLenderNote extends BaseModel
{
    protected $table = 'user_group_lender_note';

    protected $fillable = [
        'lenderid',
        'adminid',
        'notes',
        'dts',
    ];

    public $timestamps = false;
}
