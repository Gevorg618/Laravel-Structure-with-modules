<?php

namespace App\Models\Appraisal\QC;

use App\Models\BaseModel;

class ApprQc extends BaseModel
{
    const APPR_QC_TYPE_MANUAL = 'manual';
    const APPR_QC_TYPE_REALVIEW = 'realview';
    const APPR_QC_TYPE_REALVIEW_HTML = 'realviewhtml';
    const APPR_QC_TYPE_BYPASS = 'bypass';

    protected $table = 'appr_qc';

    protected $fillable = [];

    public $timestamps = false;
}
