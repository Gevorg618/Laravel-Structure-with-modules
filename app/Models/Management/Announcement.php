<?php

namespace App\Models\Management;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends BaseModel
{
    use SoftDeletes;

    protected $table = 'announcement';

    protected $fillable = [
        'title',
        'content',
        'is_active',
        'from_date',
        'created_date',
        'created_by',
        'expired_date',
        'redirect_link',
        'redirect_title'
    ];

    protected $with = [
        'userData',
        'userType',
        'viewed'
    ];

    public $timestamps = false;

    public function userData()
    {
        return $this->hasOne('App\Models\Users\UserData', 'user_id', 'created_by');
    }

    public function userType()
    {
        return $this->belongsToMany('App\Models\Management\UserType', 'announcement_visible', 'message_id','user_type');
    }

    public function viewed()
    {
        return $this->belongsToMany('App\Models\Users\User', 'announcement_viewed', 'message_id',
            'user_id')->withPivot('created_date');
    }
}
