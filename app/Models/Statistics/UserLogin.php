<?php

namespace App\Models\Statistics;

use App\Models\BaseModel;

class UserLogin extends BaseModel
{
    protected $table = 'user_logins';


    protected $fillable = [
        'userid',
        'dts',
        'login'
    ];

    /**
     * Types for login and logout
     * @var array
     */
    public $types = [
        'I' => 'Login',
        'O' => 'Logout'
    ];

    public $timestamps = true;

    /**
     * Get user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\Models\Users\User', 'userid', 'id');
    }

    /**
     * Scope for filtering
     * @param $query
     * @param $filter
     * @return mixed
     */
    public function scopeFilter($query, $filter)
    {
        if(!empty($filter['date_from'])){
            $query->where('dts','>=', $filter['date_from']);
        }
        if(!empty($filter['date_to'])){
            $query->where('dts','<=',$filter['date_to']);
        }
        if(!empty($filter['admin'])){
            $query->where('userid','=',$filter['admin']);
        }
        if(!empty($filter['log_type'])){
            $query->where('type_id','=',$filter['log_type']);
        }
        return $query;
    }
}
