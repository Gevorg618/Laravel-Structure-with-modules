<?php

namespace App\Models\Appraisal\DelayCodes;

use Illuminate\Database\Eloquent\Model;

class DelayCodes extends Model
{
    protected $table = 'appr_order_delay_code';

    public $timestamps = false;

    public static function getById($id)
    {
        return self::where('type_id', $id)->get();
    }
}
