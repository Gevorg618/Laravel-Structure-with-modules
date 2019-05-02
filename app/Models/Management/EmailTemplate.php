<?php

namespace App\Models\Management;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTemplate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'email_templates';
    protected $fillable = ['title','category','content','created_at'];

    public $timestamps = false;
}
