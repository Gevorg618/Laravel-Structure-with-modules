<?php

namespace App\Models;


use App\Models\Clients\Client;

class UserExclude extends BaseModel
{
    protected $table = 'user_exclude';

    public $timestamps = false;

    protected $primaryKey = ['group_id', 'appr_id'];

    /**
     * Client relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function groupData()
    {
        return $this->belongsTo(Client::class, 'groupid');
    }
}
