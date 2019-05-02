<?php

namespace App\Models\Documents;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentType extends BaseModel
{
    use SoftDeletes;

    protected $table = 'document_type';
    protected $fillable = ['code','name'];

    public $timestamps = false;

    public static function getDocumentTypeList()
    {
      return self::select('id', 'name', 'code')->orderBy('name', 'ASC')->get();
    }
}
