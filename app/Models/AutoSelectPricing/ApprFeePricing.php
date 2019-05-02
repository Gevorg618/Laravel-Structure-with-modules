<?php

namespace App\Models\AutoSelectPricing;

use App\Models\BaseModel;

class ApprFeePricing extends BaseModel
{
    protected $table = 'appr_fee_pricing';

    public $timestamps = false;

    protected $fillable = [
        'appr_type',
        'state',
        'amount',
        'fhaamount',
    ];
}
