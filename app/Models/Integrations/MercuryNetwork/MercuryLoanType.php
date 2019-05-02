<?php

namespace App\Models\Integrations\MercuryNetwork;

use App\Models\BaseModel;

class MercuryLoanType extends BaseModel
{
    /**
   * The table associated with the model.
   *
   * @var string
   */
    protected $table = 'mercury_loan_types';

    protected $fillable = ['title', 'external_id'];


    public function allTypes()
    {
        return $this->orderBy('title', 'ASC')->get();
    }
}
