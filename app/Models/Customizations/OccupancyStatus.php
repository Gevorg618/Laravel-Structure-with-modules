<?php

namespace App\Models\Customizations;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class OccupancyStatus extends BaseModel
{
    use SoftDeletes;

    protected $table = 'occstatus';
    protected $fillable = ['descrip','mismo_label','is_protected'];

    public $timestamps = false;
}
