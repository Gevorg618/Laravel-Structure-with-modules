<?php

namespace App\Models\Appraisal;

use App\Models\BaseModel;

class CimCheckPayment extends BaseModel
{
    protected $table = 'appr_docuvault_cim_check_payments';

    protected $fillable = [
        'order_id',
        'created_date',
        'user_id',
        'amount',
        'ref_type',
        'check_number',
        'date_received',
        'check_from',
        'is_visible',
    ];

    public $timestamps = false;
}
