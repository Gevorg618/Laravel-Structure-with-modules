<?php

namespace App\Models\Integrations\FNC;

use App\Models\BaseModel;

class FNCStatusRelation extends BaseModel
{
    protected $table = 'fnc_order_status_relation';

    protected $fillable = ['fnc_status_id', 'lni_status_id'];

    public $timestamps = false;
}
