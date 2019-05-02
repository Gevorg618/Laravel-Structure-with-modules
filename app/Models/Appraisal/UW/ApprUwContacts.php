<?php

namespace App\Models\Appraisal\UW;

use App\Models\BaseModel;

class ApprUwContacts extends BaseModel
{
    protected $table = 'appr_uw_contacts';

    protected $fillable = [
        'uw_id',
        'created_by',
        'created_date',
        'name',
        'email',
    ];

    public $timestamps = false;
}
