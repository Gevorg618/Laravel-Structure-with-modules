<?php

namespace App\Models\Management;

use App\Models\BaseModel;

class ZipCode extends BaseModel
{
    protected $table = 'zip_code';
    protected $fillable = ['zip_code','type','city','county','state','long','lat','ndc','nomls'];
    public $timestamps = false;

    public function stateRow()
    {
        return $this->belongsTo('App\Models\Geo\State','state','abbr');
    }
}
