<?php

namespace App\Models\Integrations\ValuTrac;

use App\Models\BaseModel;

class OrderRelation extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'valutrac_order_relation';
    protected $fillable = ['valutrac_oid', 'lni_oid'];

    public $timestamps = false;
}
