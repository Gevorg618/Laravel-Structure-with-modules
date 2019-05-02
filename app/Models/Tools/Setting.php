<?php

namespace App\Models\Tools;

use DB;
use Cache;
use Exception;
use Carbon\Carbon;
use App\Models\BaseModel;
use App\Models\Clients\Client;
use App\Models\Appraisal\Type;
use App\Models\Appraisal\Status;
use App\Models\Appraisal\LoanReason;
use App\Models\Mail\EmailTemplate;
use App\Models\Tools\SettingCategory;

class Setting extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'setting';
    protected static $_cachedLists = [];
    protected static $settings = [];
    protected static $settingCategories = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    const SETTINGS_CACHE_KEY = 'settings.all';

    public static $settingTypes = [
      'textfield' => 'Text Field',
      'textarea' => 'Text Area',
      'dropdown' => 'Drop Down',
      'checkbox' => 'Checkbox Button(s)',
      'radio' => 'Radio Button(s)',
      'date' => 'Date Selector',
      'datetime' => 'Date Time Selector',
      'multi' => 'Multi Select Box',
      'yesno' => 'Yes/No Checkbox',
      'editor' => 'HTML Editor',
    ];

    protected $fillable = [
      'category_id',
      'title',
      'description',
      'type',
      'value',
      'default_value',
      'ord',
      'extra',
      'is_protected',
      'setting_key'
    ];

    public static function getSetting($key)
    {
        return static::getSettings()[$key] ?? null;
    }

    public function afterSave()
    {
        $this->resetCache();

        return parent::afterSave();
    }

    public static function resetCache()
    {
        Cache::forget(static::SETTINGS_CACHE_KEY);
    }

    public static function getSettings()
    {
        if ($cached = Cache::get(static::SETTINGS_CACHE_KEY)) {
            return $cached;
        }

        $settings = collect();
        Setting::select(['id', 'setting_key', 'title', 'value', 'default_value'])->get()->each(function ($item) use ($settings) {
            $settings[$item->setting_key] = $item->getValue();
        });

        Cache::put(static::SETTINGS_CACHE_KEY, $settings, Carbon::now()->addWeek());

        return $settings;
    }

    public function getSettingKey($title)
    {
        return str_slug($title, '_');
    }

    /**
       * @param string     $key
       * @param null|mixed $default
       */
    public static function setting($key, $default = null)
    {
        if (!static::$settings) {
            static::$settings = static::getSettings();
        }

        if (isset(static::$settings[$key])) {
            return static::$settings[$key];
        }

        return $default;
    }

    public function beforeSave()
    {
        if (!$this->setting_key) {
            $this->setting_key = $this->getSettingKey($this->title);
        }

        // Make sure we have a proper type
        if (!$this->type || !isset(static::$settingTypes[$this->type])) {
            throw new Exception(sprintf("Setting type '%s' is invalid", $this->type));
        }

        // Fix the category id being a string or key
        if (intval($this->category_id) !== $this->category_id && is_string($this->category_id) && ($key = SettingCategory::getIdByKey($this->category_id))) {
            $this->category_id = $key;
        }

        if (!$this->ord) {
            $max = DB::table($this->table)->where('category_id', $this->category_id)->max('ord');
            $this->ord = ++$max;
        }

        return parent::beforeSave();
    }

    public function getValue()
    {
        return $this->value != '' ? $this->value : $this->default_value;
    }

    public function getSelectedItems()
    {
        return explode(',', $this->getValue());
    }

    public function isChecked($key)
    {
        $checkedValues = explode(',', $this->getValue());
        return $checkedValues && in_array($key, $checkedValues);
    }

    protected function _getValueForSave($value)
    {
        if (is_array($value) && count($value)) {
            $value = implode(',', $value);
        }

        return $value;
    }

    public function saveSetting($id, $value)
    {
        static::where('id', $id)->update(['value' => $this->_getValueForSave($value)]);
    }

    public function getSettingDropDownOptions()
    {
        $list = collect([]);

        $temp = trim($this->extra);

        if (!$temp) {
            return $list;
        }

        if (strpos($temp, '#')!==false && isset(static::$_cachedLists[$temp])) {
            return static::$_cachedLists[$temp];
        }

        switch ($temp) {
      case '#clients#':
        return static::$_cachedLists[$temp] = Client::take(500)->get()->pluck('descrip', 'id');

      case '#appr_types#':
        return static::$_cachedLists[$temp] = Type::get()->pluck('descrip', 'id');

      case '#states#':
        return static::$_cachedLists[$temp] = getStates();

      case '#appr_statuses#':
        return static::$_cachedLists[$temp] = Status::get()->pluck('descrip', 'id');

      case '#loan_purposes#':
        return static::$_cachedLists[$temp] = LoanReason::get()->pluck('descrip', 'id');

      case '#email_templates#':
        return static::$_cachedLists[$temp] = EmailTemplate::get()->pluck('title', 'id');

      case '#time_zones#':
        return static::$_cachedLists[$temp] = getTimeZoneList();

      case '#amcs#':
        return [];
    }

        // Convert list items to dropdown
        $explode = explode("\n", $temp);
        if ($explode && count($explode)) {
            foreach ($explode as $item) {
                $split = explode("=", $item);
        
                $k = '';
                $v = '';
                if ($split[0]) {
                    $k = $split[0];
                }
        
                if ($split[1]) {
                    $v = $split[1];
                }

                if (!$k && $v) {
                    $k = $v;
                }
        
                $list[$k] = $v;
            }
        }

        return $list;
    }
}
