<?php

namespace App\Models\Clients;

use App\Models\Clients\Client;
use App\Models\Users\User;
use App\Models\BaseModel;

class UserGroupNote extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_group_note';

    protected  $fillable = ['groupid', 'adminid', 'notes', 'dts', 'message'];

    public  $timestamps = false;

    /**
     * @return $this
     */
    public function group()
    {
        return $this->belongsTo(Client::class, 'groupid')->with('adminTeamClient');
    }

    /**
     * @return $this
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'adminid')->with('userData');
    }
}
