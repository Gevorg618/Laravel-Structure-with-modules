<?php

namespace App\Models\Tools;

use DB;
use App\Models\BaseModel;

class SettingCategory extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'setting_category';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['title', 'ord', 'description'];

    public static $rules = [
    'title' => 'required|min:3|max:55',
    'description' => 'max:155',
  ];

    public function beforeSave()
    {
        if (!$this->key) {
            $this->key = str_slug($this->title, '_');
        }

        if (!$this->ord) {
            $max = DB::table($this->table)->max('ord');
            $this->ord = ++$max;
        }

        return parent::beforeSave();
    }

    public static function getIdByKey($code)
    {
        $row = static::where('key', $code)->first();

        return $row->id ?? null;
    }

    public function settings()
    {
        return $this->hasMany('App\Models\Tools\Setting', 'category_id');
    }
}
