<?php

namespace App\Models\Integrations\FNC;

use App\Models\BaseModel;

class FNCLoanTypes extends BaseModel
{
    protected $table = 'fnc_loan_types';

    protected $fillable = ['key', 'value'];

    public function allTypes()
    {
        return $this->orderBy('value', 'ASC')->get();
    }
}
