<?php

namespace App\Models\Management\WholesaleLenders;

use App\Models\BaseModel;

class UserGroupLenderUwContactInfo extends BaseModel
{
    protected $table = 'user_group_lender_uw_contact_info';

    protected $fillable = [
        'lenderid',
        'created_by',
        'full_name',
        'email',
        "phone",
        'created_at'
    ];

    public $timestamps = false;
}
