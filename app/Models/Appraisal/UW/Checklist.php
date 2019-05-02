<?php

namespace App\Models\Appraisal\UW;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checklist extends BaseModel
{
    use SoftDeletes;

    protected $table = 'appr_uw_checklist';
    protected $fillable = ['title','correction','is_active','category_id'];
    protected $with = ['appr_type','loan_type','loan_reason','clients','lenders'];
    public $timestamps = false;

    public static function boot() {
        parent::boot();

        static::deleting(function($question) { // before delete() method call this
            $question->appr_type()->detach();
            $question->loan_type()->detach();
            $question->loan_reason()->detach();
            $question->clients()->detach();
            $question->lenders()->detach();
        });

    }
    public function appr_type()
    {
        return $this->belongsToMany('App\Models\Customizations\Type','appr_uw_checklist_appr_type_rel','rel_id','local_id');
    }
    public function loan_type()
    {
        return $this->belongsToMany('App\Models\Customizations\LoanType','appr_uw_checklist_loan_type_rel','rel_id','local_id');
    }
    public function loan_reason()
    {
        return $this->belongsToMany('App\Models\Customizations\LoanReason','appr_uw_checklist_loan_reason_rel','rel_id','local_id');
    }
    public function clients()
    {
        return $this->belongsToMany('App\Models\Clients\Client','appr_uw_checklist_client_rel','rel_id','local_id');
    }
    public function lenders()
    {
        return $this->belongsToMany('App\Models\Management\WholesaleLenders\UserGroupLender','appr_uw_checklist_lender_rel','rel_id','local_id');
    }

    public static function saveRelations($create,$data)
    {
        if (!empty($data['appr_type'])) {
            $create->appr_type()->attach($data['appr_type']);
        }
        if (!empty($data['loan_type'])) {
            $create->loan_type()->attach($data['loan_type']);
        }
        if (!empty($data['loan_reason'])) {
            $create->loan_reason()->attach($data['loan_reason']);
        }
        if (!empty($data['clients'])) {
            $create->clients()->attach($data['clients']);
        }
        if (!empty($data['lenders'])) {
            $create->lenders()->attach($data['lenders']);
        }
    }

    public static function updateRelations($question, $data)
    {
        if (!empty($data['appr_type'])) {
            $question->appr_type()->sync($data['appr_type']);
        } else {
            $question->appr_type()->detach();
        }
        if (!empty($data['loan_type'])) {
            $question->loan_type()->sync($data['loan_type']);
        } else {
            $question->loan_type()->detach();
        }
        if (!empty($data['loan_reason'])) {
            $question->loan_reason()->sync($data['loan_reason']);
        } else {
            $question->loan_reason()->detach();
        }
        if (!empty($data['clients'])) {
            $question->clients()->sync($data['clients']);
        } else {
            $question->clients()->detach();
        }
        if (!empty($data['lenders'])) {
            $question->lenders()->sync($data['lenders']);
        } else {
            $question->lenders()->detach();
        }
    }
}
