<?php

namespace App\Models\Tools;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends BaseModel
{
    use SoftDeletes;
    protected $table = 'templates';
    protected $fillable = ['name','source','created_by','last_modified_by','last_modified','created_date'];
    public $timestamps = false;
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\Users\UserData','created_by','user_id');
    }
    public function test()
    {
        return $this->belongsTo('App\Models\Users\UserData','last_modified_by','user_id');
    }
}
