<?php

namespace App\Models\Appraisal\UW;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
class ChecklistCategory extends BaseModel
{
    use SoftDeletes;

    protected $table = 'appr_uw_checklist_category';
    protected $fillable = ['title','is_active'];
    protected $with = ['questions'];
    public $timestamps = false;

    public function questions()
    {
        return $this->hasMany('App\Models\Appraisal\UW\Checklist','category_id');
    }
}
