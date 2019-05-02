<?php

namespace App\Models\Appraisal\MailPipeline;

use Illuminate\Database\Eloquent\Model;

class SentMail extends Model
{
    protected $table = 'appr_sent_mail';

    public $timestamps = false;

    protected $fillable = [
        'is_ready',
        'is_failed',
        'delivered_date',
        'tracking_number',
        'sent_date',
        'sent_by'
    ];

    public function createdBy()
    {
        return $this->hasMany('App\Models\Users\UserData','user_id','created_by')->select('user_id', 'firstname', 'lastname');
    }

    public function sentBy()
    {
        return $this->hasMany('App\Models\Users\UserData','user_id','sent_by')->select('user_id', 'firstname', 'lastname');
    }

}
