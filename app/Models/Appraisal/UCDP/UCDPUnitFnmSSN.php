<?php

namespace App\Models\Appraisal\UCDP;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class UCDPUnitFnmSSN extends BaseModel
{
    use SoftDeletes;

    protected $table = 'ucdp_unit_fnm_ssn';
    protected $fillable = ['rel_id','ssn_id','title'];
    public $timestamps = false;
}
