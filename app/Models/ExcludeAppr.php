<?php

namespace App\Models;

use App\Models\Users\UserData;
use App\Models\Users\User;

class ExcludeAppr extends BaseModel
{
    protected $table = 'user_exclude';

    public $timestamps = false;




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

}
