<?php

namespace App\Models\Customizations;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanReason extends BaseModel
{
    use SoftDeletes;

    protected $table = 'loanpurpose';

    protected $fillable = ['descrip', 'mismo_label', 'is_protected'];

    public $timestamps = false;

    public function allReasons()
    {
        return $this->orderBy('descrip', 'ASC')->get();
    }

    public static function getReasons()
    {
        return self::select('id', 'descrip')->orderBy('descrip', 'ASC')->get();
    }
}
