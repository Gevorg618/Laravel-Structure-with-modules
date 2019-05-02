<?php

namespace App\Models\Appraisal\QC;

use App\Models\BaseModel;

class QcAnswer extends BaseModel
{
    protected $table = 'appr_qc_answers';

    protected $fillable = [];

    public $timestamps = false;
}
