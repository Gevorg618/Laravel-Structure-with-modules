<?php

namespace App\Models\Integrations\FNC;

use App\Models\BaseModel;

class FNCLoanReason extends BaseModel
{
    protected $table = 'fnc_loan_reason';

    protected $fillable = ['key', 'value'];

    public function allReasons()
    {
        return $this->orderBy('key', 'ASC')->get();
    }
}
