<?php

namespace App\Models\Appraisal\UCDP;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class UCDPUnit extends BaseModel
{
    use SoftDeletes;

    protected $table = 'ucdp_unit';
    protected $fillable = ['unit_id','title','is_active','fnm_active','fre_active'];
    protected $with = ['appr_type','loan_type','clients','lenders','fnm_ssn','fre_ssn'];
    public $timestamps = false;

    public static function boot() {
        parent::boot();

        static::deleting(function($unit) { // before delete() method call this
            $unit->fre_ssn()->delete();
            $unit->fnm_ssn()->delete();
            $unit->appr_type()->detach();
            $unit->loan_type()->detach();
            $unit->clients()->detach();
            $unit->lenders()->detach();
        });

    }

    public function appr_type()
    {
        return $this->belongsToMany('App\Models\Customizations\Type','ucdp_unit_appraisal_type_rel','rel_id','appr_id');
    }
    public function loan_type()
    {
        return $this->belongsToMany('App\Models\Customizations\LoanType','ucdp_unit_loan_type_rel','rel_id','loan_id');
    }
    public function clients()
    {
        return $this->belongsToMany('App\Models\Clients\Client','ucdp_unit_client_rel','rel_id','client_id');
    }
    public function lenders()
    {
        return $this->belongsToMany('App\Models\Management\WholesaleLenders\UserGroupLender','ucdp_unit_lender_rel','rel_id','lender_id');
    }

    public function fnm_ssn()
    {
        return $this->hasMany('App\Models\Appraisal\UCDP\UCDPUnitFnmSSN','rel_id','id');
    }
    public function fre_ssn()
    {
        return $this->hasMany('App\Models\Appraisal\UCDP\UCDPUnitFreSSN','rel_id','id');
    }

    public static function saveRelations($create,$data)
    {
        if (!empty($data['appr_type'])) {
            $create->appr_type()->attach($data['appr_type']);
        }
        if (!empty($data['loan_type'])) {
            $create->loan_type()->attach($data['loan_type']);
        }
        if (!empty($data['clients'])) {
            $create->clients()->attach($data['clients']);
        }
        if (!empty($data['lenders'])) {
            $create->lenders()->attach($data['lenders']);
        }
        if (!empty($data['fnm_ssn'])) {
            $create->fnm_ssn()->createMany($data['fnm_ssn']);
        }
        if (!empty($data['fre_ssn'])) {
            $create->fre_ssn()->createMany($data['fre_ssn']);
        }
    }

    public static function updateRelations($ucdpUnit, $data)
    {
        if (!empty($data['appr_type'])) {
            $ucdpUnit->appr_type()->sync($data['appr_type']);
        } else {
            $ucdpUnit->appr_type()->detach();
        }
        if (!empty($data['loan_type'])) {
            $ucdpUnit->loan_type()->sync($data['loan_type']);
        } else {
            $ucdpUnit->loan_type()->detach();
        }
        if (!empty($data['clients'])) {
            $ucdpUnit->clients()->sync($data['clients']);
        } else {
            $ucdpUnit->clients()->detach();
        }
        if (!empty($data['lenders'])) {
            $ucdpUnit->lenders()->sync($data['lenders']);
        } else {
            $ucdpUnit->lenders()->detach();
        }
        if (!empty($data['fnm_ssn'])) {
            $ucdpUnit->fnm_ssn()->createMany($data['fnm_ssn']);
        }
        if (!empty($data['fre_ssn'])) {
            $ucdpUnit->fre_ssn()->createMany($data['fre_ssn']);
        }
    }
}
