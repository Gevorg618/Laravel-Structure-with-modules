<?php

namespace App\Models\OrderDocuments;

use App\Models\BaseModel;

class State extends BaseModel
{
    protected $table = 'order_documents_state';

    public $timestamps = false;

    /**
     * @param $query
     * @param string $state
     * @return mixed
     */
    public function scopeOfState($query, $state)
    {
        return $query->where('state', $state)->orWhereNull('state');
    }
}
