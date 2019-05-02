<?php

namespace App\Models\Appraisal\UW;

use App\Models\BaseModel;

class UW extends BaseModel
{
    protected $table = 'appr_uw';

    protected $fillable = [
        'is_cu_risk_hold',
        'is_hold',
        'is_complete',
        'locked_by',
        'locked_date',
        'is_saved',
        'order_id',
        'created_by',
        'created_date',
        'send_support_emails',
        'send_final_report_emails',
        'send_to_client',
        'send_to_appr',
    ];

    public $timestamps = false;
}
