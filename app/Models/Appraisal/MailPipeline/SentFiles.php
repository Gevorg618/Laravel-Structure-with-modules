<?php

namespace App\Models\Appraisal\MailPipeline;

use Illuminate\Database\Eloquent\Model;

class SentFiles extends Model
{
    protected $table = 'appr_sent_mail_files';

    public $timestamps = false;

    protected $fillable = [
        'orderid',
        'fileid',
        'apprsentid'
    ];
}
