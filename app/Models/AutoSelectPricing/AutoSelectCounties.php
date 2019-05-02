<?php

namespace App\Models\AutoSelectPricing;

use App\Models\BaseModel;

class AutoSelectCounties extends BaseModel
{
    protected $table = 'appr_autoselect_counties';

    protected $fillable = ['id', 'state', 'county'];
}