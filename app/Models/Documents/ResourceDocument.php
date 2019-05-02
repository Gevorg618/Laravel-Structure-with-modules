<?php

namespace App\Models\Documents;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class ResourceDocument extends BaseModel
{
    use SoftDeletes;

    protected $table = 'resource_document';
    protected $fillable = ['title','description','link','type','created_by'];

    public function user()
    {
        return $this->belongsTo('App\Models\Users\UserData','created_by','user_id');
    }
}
