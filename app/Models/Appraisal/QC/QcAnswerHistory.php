<?php

namespace App\Models\Appraisal\QC;

use App\Models\BaseModel;

class QcAnswerHistory extends BaseModel
{
    protected $table = 'appr_qc_answers_history';

    protected $fillable = [];

    public $timestamps = false;
}
