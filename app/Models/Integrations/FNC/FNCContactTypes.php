<?php

namespace App\Models\Integrations\FNC;

use App\Models\BaseModel;

class FNCContactTypes extends BaseModel
{
    protected $table = 'fnc_contact_types';

    protected $fillable = ['key', 'value'];
}
