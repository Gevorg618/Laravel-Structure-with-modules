<?php

namespace App\Models\Integrations\FNC;

use App\Models\BaseModel;

class FNCPropertyTypes extends BaseModel
{
    protected $table = 'fnc_property_types';

    protected $fillable = ['key', 'value'];

    public function allTypes()
    {
        return $this->orderBy('key', 'ASC')->get();
    }
}
