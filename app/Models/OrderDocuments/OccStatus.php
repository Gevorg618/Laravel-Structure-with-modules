<?php

namespace App\Models\OrderDocuments;

use App\Models\BaseModel;

class OccStatus extends BaseModel
{
    protected $table = 'order_documents_occ_status';

    public $timestamps = false;

    /**
     * @param $query
     * @param int $type
     * @return mixed
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type_id', $type)->orWhereNull('type_id');
    }
}
