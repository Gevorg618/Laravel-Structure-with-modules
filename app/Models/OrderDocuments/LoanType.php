<?php

namespace App\Models\OrderDocuments;

use App\Models\BaseModel;

class LoanType extends BaseModel
{
    protected $table = 'order_documents_loan_type';

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
