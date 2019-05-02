<?php

namespace App\Models\OrderDocuments;

use App\Models\BaseModel;

class Location extends BaseModel
{
    protected $table = 'order_documents_location_relation';

    public $timestamps = false;

}
