<?php

namespace App\Models\Integrations\APIUsers;

use App\Models\BaseModel;

class APIUserGroup extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'api_user_group';

    protected $fillable = [
            'api_id',
            'group_id'
        ];

    public $timestamps = false;

    public static function getSavedApiUsers($id)
    {
        return self::where('api_id', $id)->get();
    }
}
