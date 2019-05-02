<?php

namespace App\Models\Management;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTemplate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'user_templates';
    protected $fillable = ['title','user_id','content','is_approved','created_at'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\Users\UserData','user_id','user_id');
    }
}
