<?php

namespace App\Models\Customizations;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanType extends BaseModel
{
    use SoftDeletes;

    protected $table = 'loantype';

    protected $fillable = ['descrip','mismo_label','is_protected','is_default'];

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    public static function getLoanTypes()
    {
        return self::select('id', 'descrip')->orderBy('descrip')->get();
    }
}
