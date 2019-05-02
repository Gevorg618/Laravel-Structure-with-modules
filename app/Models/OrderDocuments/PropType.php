<?php

namespace App\Models\OrderDocuments;

use App\Models\BaseModel;

class PropType extends BaseModel
{
    protected $table = 'order_documents_prop_type';

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
