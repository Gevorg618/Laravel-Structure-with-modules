<?php

namespace App\Models\Appraisal;

use App\Models\BaseModel;

class Cert extends BaseModel
{
    protected $table = 'appr_cert';

    public $timestamps = false;

    protected $fillable = ['cert_num', 'cert_expire'];
}
