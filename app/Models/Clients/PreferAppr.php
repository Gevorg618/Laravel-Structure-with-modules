<?php

namespace App\Models\Clients;



use App\Models\BaseModel;
use App\Models\Users\UserData;
use App\Models\Users\User;

class PreferAppr extends BaseModel
{
    protected $table = 'prefer_appr';

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function groupData()
    {
        return $this->belongsTo(Client::class, 'groupid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userData()
    {
        return $this->belongsTo(UserData::class, 'apprid', 'user_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'apprid', 'id');
    }

    /**
     * @return null
     */
    public function getGroupNameAttribute()
    {
        if ($this->groupData) {
            return $this->groupData->descrip;
        }
        return null;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clients()
    {
        return $this->belongsTo('App\Models\Clients\Client', 'groupid', 'apprid');
    }




}
