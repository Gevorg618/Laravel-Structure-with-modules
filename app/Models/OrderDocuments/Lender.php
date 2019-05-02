<?php

namespace App\Models\OrderDocuments;

use App\Models\BaseModel;

class Lender extends BaseModel
{
    protected $table = 'order_documents_lender';

    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['file_id', 'lender_id'];
}
