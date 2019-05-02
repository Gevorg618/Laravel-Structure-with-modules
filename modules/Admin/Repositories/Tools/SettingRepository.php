<?php

namespace Modules\Admin\Repositories\Tools;

use App\Models\Tools\Setting;


class SettingRepository
{   
    /**
     * Object of Setting class.
     *
     * @var $setting
     */
    private $setting;

    /**
     * Create a new instance of SettingRepository class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setting = new Setting();
    }

    /**
     * get setting
     *
     * @return  collection
     */
    public function getSettingsByKey($key)
    {
        return $this->setting->getSetting($key);
    }
}    