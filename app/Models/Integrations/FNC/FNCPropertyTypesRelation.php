<?php

namespace App\Models\Integrations\FNC;

use App\Models\BaseModel;

class FNCPropertyTypesRelation extends BaseModel
{
    protected $table = 'fnc_order_property_type_relation';

    protected $fillable = ['fnc_type_id', 'lni_type_id'];

    public $timestamps = false;
}
