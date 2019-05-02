<?php

namespace App\Models\Integrations\MercuryNetwork;

use App\Models\BaseModel;

class MercuryColumnMap extends BaseModel
{
    /**
   * The table associated with the model.
   *
   * @var string
   */
    protected $table = 'mercury_column_map';

    protected $fillable = ['key', 'value'];
}
