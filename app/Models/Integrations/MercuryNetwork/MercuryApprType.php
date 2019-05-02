<?php

namespace App\Models\Integrations\MercuryNetwork;

use App\Models\BaseModel;

class MercuryApprType extends BaseModel
{
    /**
   * The table associated with the model.
   *
   * @var string
   */
    protected $table = 'mercury_appr_types';

    protected $fillable = ['external_id' ,'title'];

    public function allTypes()
    {
        return $this->orderBy('title', 'ASC')->get();
    }
}
