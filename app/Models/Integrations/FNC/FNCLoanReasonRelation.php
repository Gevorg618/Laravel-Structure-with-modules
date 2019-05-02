<?php

namespace App\Models\Integrations\FNC;

use App\Models\BaseModel;

class FNCLoanReasonRelation extends BaseModel
{
    protected $table = 'fnc_order_loan_reason_relation';

    protected $fillable = ['fnc_type_id', 'lni_type_id'];

    public $timestamps = false;
}
