<?php

namespace App\Models\Appraisal\QC;

use App\Models\BaseModel;

class DataQuestionLoanReason extends BaseModel
{
    protected $table = 'appr_qc_data_collection_question_loan_reason';

    protected $fillable = [];

    public $timestamps = false;
}
