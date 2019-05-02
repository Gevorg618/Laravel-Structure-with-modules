<?php

namespace App\Models\Integrations\FNC;

use App\Models\BaseModel;

class FNCStatuses extends BaseModel
{
    protected $table = 'fnc_statuses';

    protected $fillable = ['key', 'value'];

    public function allStatuses()
    {
        return $this->orderBy('key', 'ASC')->get();
    }
}
