<?php

namespace App\Models\Customizations;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Addenda extends BaseModel
{
    use SoftDeletes;

    protected $table = 'addendas';
    protected $fillable = ['descrip','invest','price','is_protected'];

    public $timestamps = false;
}
