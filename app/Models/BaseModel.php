<?php

namespace App\Models;

use DB, Config, Log, Elasticsearch, Cache;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BaseModel extends Model
{
    public static function boot()
    {
        parent::boot();

        /**
         * Copied from Ardent for event management
         *
         */

        $myself = get_called_class();
        $hooks = ['before' => 'ing', 'after' => 'ed'];
        $radicals = ['sav', 'creat', 'updat', 'delet'];

        foreach ($radicals as $rad) {
            foreach ($hooks as $hook => $event) {
                $method = $hook . ucfirst($rad) . 'e';
                if (method_exists($myself, $method)) {
                    $eventMethod = $rad . $event;
                    self::$eventMethod(function ($model) use ($method) {
                        return $model->$method($model);
                    });
                }
            }
        }

        if(method_exists($myself, 'afterFind')) {
            static::found(function ($model) {
                return $model->afterFind();
            });
        }
    }

    public function beforeSave() {}
    public function beforeCreate() {}
    public function beforeUpdate() {}
    public function beforeDelete() {}
    public function afterSave() {}
    public function afterCreate() {}
    public function afterUpdate() {}
    public function afterDelete() {}
}
