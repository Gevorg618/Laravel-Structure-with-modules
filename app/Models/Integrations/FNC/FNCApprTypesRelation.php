<?php

namespace App\Models\Integrations\FNC;

use App\Models\BaseModel;

class FNCApprTypesRelation extends BaseModel
{
    protected $table = 'fnc_order_appraisal_type_relation';

    protected $fillable = ['fnc_type_id', 'lni_type_id', 'property_type_id', 'occ_type_id', 'addendas'];

    public $timestamps = false;
}
