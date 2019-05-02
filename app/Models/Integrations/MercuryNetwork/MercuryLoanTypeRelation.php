<?php

namespace App\Models\Integrations\MercuryNetwork;

use App\Models\BaseModel;

class MercuryLoanTypeRelation extends BaseModel
{
    /**
   * The table associated with the model.
   *
   * @var string
   */
    protected $table = 'mercury_order_loan_type_relation';

    protected $fillable = ['mercury_type_id', 'lni_type_id', 'lni_reason_id'];

    public $timestamps = false;
}
