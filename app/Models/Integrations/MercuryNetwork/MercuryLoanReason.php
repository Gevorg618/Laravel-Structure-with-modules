<?php

namespace App\Models\Integrations\MercuryNetwork;

use App\Models\BaseModel;

class MercuryLoanReason extends BaseModel
{
    /**
   * The table associated with the model.
   *
   * @var string
   */
  	protected $table = 'mercury_loan_reason';

  	protected $fillable = ['title', 'external_id'];

  	public function allReasons()
  	{
  		return $this->orderBy('title', 'ASC')->get();
  	}
}
