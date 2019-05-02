<?php

namespace App\Models;

class UserGroupLog extends BaseModel
{
    protected $table = 'user_group_log';


    protected $fillable = [
        'groupid',
        'created_date',
        'message',
        'created_by'

    ];

    public $timestamps = false;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany('App\Models\Appraisal\Order', 'user_group_log_order_id', 'logid', 'orderid');
    }
}
