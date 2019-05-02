<?php

namespace App\Models\Integrations\MercuryNetwork;

use App\Models\BaseModel;

class MercuryStatusRelation extends BaseModel
{
   /**
   * The table associated with the model.
   *
   * @var string
   */
    protected $table = 'mercury_order_status_relation';

    protected $fillable = ['mercury_status_id', 'lni_status_id'];

    public $timestamps = false;
}
