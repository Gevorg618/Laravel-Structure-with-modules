<?php

namespace App\Models\CRM;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleStage extends BaseModel
{
    use SoftDeletes;

    protected $table = 'user_group_lead_sales_stage';

    protected $fillable = ['title','visible', 'skey'];

    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
