<?php

namespace App\Models\Integrations\MercuryNetwork;

use App\Models\BaseModel;

class MercuryApprTypeRelation extends BaseModel
{
    /**
   * The table associated with the model.
   *
   * @var string
   */
    protected $table = 'mercury_order_appraisal_type_relation';

    protected $fillable = ['mercury_type_id' ,'lni_type_id', 'property_type_id', 'occ_type_id', 'addendas'];

    public $timestamps = false;

    public function getSavedData()
    {
        return $this->all();
    }
}
