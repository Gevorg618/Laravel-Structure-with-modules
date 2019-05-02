<?php

namespace App\Models\Documents;

use App\Models\BaseModel;

class OrderLocation extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_documents_locations';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
    ];
}
