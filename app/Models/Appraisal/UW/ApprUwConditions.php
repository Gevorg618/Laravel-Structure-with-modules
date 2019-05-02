<?php

namespace App\Models\Appraisal\UW;

use App\Models\BaseModel;

class ApprUwConditions extends BaseModel
{
    protected $table = 'appr_uw_conditions';

    protected $fillable = [
        'uw_id',
        'created_by',
        'created_date',
        'category',
        'response',
        'is_approved',
        'cond',
    ];

    public $timestamps = false;
}
