<?php

namespace App\Models\AutoSelectPricing;

use App\Models\BaseModel;

class ApprStatePricingVersion extends BaseModel
{
    protected $table = 'appr_state_price_version_row';

    public $timestamps = false;

    protected $fillable = [
        'version_id',
        'state',
        'amount',
        'appr_type',
        'fha_amount',
        'loan_type'
    ];
}
