<?php

namespace App\Models\Documents;


use App\Models\BaseModel;

class UserDocument extends BaseModel
{
    protected $table = 'user_document';

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userDocumentType()
    {
        return $this->belongsTo(UserDocumentType::class, 'type');
    }
}