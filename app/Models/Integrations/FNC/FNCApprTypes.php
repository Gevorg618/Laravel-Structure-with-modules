<?php

namespace App\Models\Integrations\FNC;

use App\Models\BaseModel;

class FNCApprTypes extends BaseModel
{
    protected $table = 'fnc_appr_types';

    protected $fillable = ['external_id', 'value'];

    public function allTypes()
    {
        return $this->orderBy('external_id',  'ASC')->get();
    }
}
