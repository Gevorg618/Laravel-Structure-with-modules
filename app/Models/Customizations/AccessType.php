<?php

namespace App\Models\Customizations;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;

class AccessType extends BaseModel
{
    use SoftDeletes;

    protected $fillable = ['name', 'is_active'];
    protected $table = 'appr_order_access_type';
    public $timestamps = false;
}