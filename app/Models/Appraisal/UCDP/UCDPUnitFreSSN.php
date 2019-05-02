<?php

namespace App\Models\Appraisal\UCDP;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class UCDPUnitFreSSN extends BaseModel
{
    use SoftDeletes;
    
    protected $table = 'ucdp_unit_fre_ssn';
    protected $fillable = ['rel_id','ssn_id','title'];
    public $timestamps = false;

}
