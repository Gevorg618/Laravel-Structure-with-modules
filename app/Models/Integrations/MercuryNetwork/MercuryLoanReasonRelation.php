<?php

namespace App\Models\Integrations\MercuryNetwork;

use App\Models\BaseModel;

class MercuryLoanReasonRelation extends BaseModel
{
    /**
   * The table associated with the model.
   *
   * @var string
   */
    protected $table = 'mercury_order_loan_reason_relation';

    protected $fillable = ['mercury_type_id', 'lni_type_id'];

    public $timestamps = false;
}
