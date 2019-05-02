<?php

namespace App\Models\Documents;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDocumentType extends BaseModel
{
    use SoftDeletes;

    protected $table = 'user_document_type';
    protected $fillable = ['code','title'];

    public $timestamps = false;
}
