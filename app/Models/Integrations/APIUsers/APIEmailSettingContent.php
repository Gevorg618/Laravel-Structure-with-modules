<?php

namespace App\Models\Integrations\APIUsers;

use App\Models\BaseModel;

class APIEmailSettingContent extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'api_email_setting_content';

    protected $fillable = [
            'api_user_id',
            'api_email_setting_id',
            'content'
        ];

    public $timestamps = false;

    public static function getAPIEmailContentByKey($id, $key)
    {
        return self::where('api_user_id', $id)->where('api_email_setting_id', $key)->first();
    }
}
