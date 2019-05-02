<?php

namespace App\Models\Appraisal;

use App\Models\BaseModel;

class OrderAddenda extends BaseModel
{
    protected $table = 'appr_order_addenda';

    /**
     * Addenda relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function addenda()
    {
        return $this->belongsTo(Addenda::class, 'addenda');
    }
}
