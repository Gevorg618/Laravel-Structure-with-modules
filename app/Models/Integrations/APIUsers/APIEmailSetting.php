<?php

namespace App\Models\Integrations\APIUsers;

use App\Models\BaseModel;

class APIEmailSetting extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'api_email_setting';

    public $timestamps = false;

    public static function getAPIEmailSettings()
    {
        return self::orderBy('title', 'ASC')->get();
    }
}
