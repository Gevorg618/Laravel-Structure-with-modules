<?php

namespace App\Models\Integrations\MercuryNetwork;

use App\Models\BaseModel;

class MercuryStatus extends BaseModel
{
    /**
   * The table associated with the model.
   *
   * @var string
   */
    protected $table = 'mercury_statuses';

    protected $fillable = ['external_id', 'title'];



    public function allStatuses()
    {
        return $this->orderBy('external_id', 'ASC')->get();
    }
}
