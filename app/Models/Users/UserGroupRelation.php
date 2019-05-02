<?php

namespace App\Models\Users;

use App\Models\Users\User;
use App\Models\BaseModel;
use App\Models\Clients\Client;

class UserGroupRelation extends BaseModel
{
    protected $table = 'user_group_relation';

    public $timestamps = false;

    protected $fillable = ['user_id', 'group_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
      return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
      return $this->belongsTo(Client::class, 'group_id', 'id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userData()
    {
        return $this->belongsTo(UserData::class, 'user_id', 'user_id');
    }


}
