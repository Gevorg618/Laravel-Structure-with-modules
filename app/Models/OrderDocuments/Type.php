<?php

namespace App\Models\OrderDocuments;

use App\Models\BaseModel;

class Type extends BaseModel
{
    protected $table = 'order_documents_appr_type';

    public $timestamps = false;

    /**
     * @param $query
     * @param int $type
     * @return mixed
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('appr_type_id', $type)->orWhereNull('appr_type_id');
    }
}
