<?php

namespace App\Models\Customizations;

use App\Models\BaseModel;
use App\Models\Traits\HasCompositePrimaryKey;

class SaleTax extends BaseModel
{
    use HasCompositePrimaryKey;

    protected $table = 'state_sale_tax';

    protected $primaryKey = ['state', 'county'];

    public $incrementing = false;

    protected $fillable = [
        'state',
        'county',
        'value'
    ];

    public $timestamps = false;
}
