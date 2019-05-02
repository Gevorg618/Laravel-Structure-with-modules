<?php

namespace App\Models\Integrations\FNC;

use App\Models\BaseModel;

class FNCLoanTypesRelation extends BaseModel
{
    protected $table = 'fnc_order_loan_type_relation';

    protected $fillable = ['fnc_type_id', 'lni_type_id', 'lni_reason_id'];

    public $timestamps = false;
}
