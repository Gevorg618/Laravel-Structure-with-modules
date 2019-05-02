<?php

namespace App\Models\Users;


use App\Models\BaseModel;

class SupervisingUser extends BaseModel
{
    protected $table = 'user_supervising_user';

    public $timestamps = false;
}
