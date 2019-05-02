<?php

namespace App\Models\Customizations;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class ValuationOrderStatus extends BaseModel
{
    use SoftDeletes;
    
    protected $table = 'alt_order_status';
    protected $fillable = ['name','code','is_protected'];

    public $timestamps = false;
}
