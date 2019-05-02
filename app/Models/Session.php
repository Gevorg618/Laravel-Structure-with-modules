<?php

namespace App\Models;

use App\Models\BaseModel;

class Session extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sessions';

    public function getFullNameAttribute()
    {
        return $this->user ? $this->user->fullname : config('constants.not_available');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Users\User', 'user_id');
    }

    public function userData()
    {
        return $this->belongsTo('App\Models\Users\UserData', 'user_id', 'user_id');
    }

    public static function getTotalActiveSessions($time)
    {
        return self::where('last_click', '>=', $time)->count();
    }

    public static function getTotalActiveGuestSessions($time)
    {
        return self::where('last_click', '>=', $time)->where('user_id', 0)->count();
    }

    public static function countActiveUsersByType($type, $time)
    {
        return self::where('user_type', $type)->where('last_click', '>=', $time)->where('is_app', 0)->groupBy('user_id')->count();
    }

    public static function getActiveUsersByType($type, $time)
    {
        return self::where('user_type', $type)->where('last_click', '>=', $time)->where('is_app', 0)->groupBy('user_id')->orderBy('last_click', 'DESC')->with('userData')->with('user')->get();
    }

    public static function countActiveUsersByTypeForApp($type, $time)
    {
        return self::where('user_type', $type)->where('last_click', '>=', $time)->where('is_app', 1)->groupBy('user_id')->count();
    }

    public static function getActiveUsersByTypeForApp($type, $time)
    {
        return self::where('user_type', $type)->where('last_click', '>=', $time)->where('is_app', 1)->groupBy('user_id')->orderBy('last_click', 'DESC')->with('userData')->with('user')->get();
    }
}
