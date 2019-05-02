<?php

namespace App\Models\Appraisal\EAD;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends BaseModel
{
    use SoftDeletes;

    protected $table = 'ead_unit';

    protected $fillable = ['unit_id','title','is_active','fha_lenderid'];
    protected $with = ['appr_type','loan_type','clients','lenders'];
    public $timestamps = false;

    public static function boot() {
        parent::boot();

        static::deleting(function($unit) { // before delete() method call this
            $unit->appr_type()->detach();
            $unit->loan_type()->detach();
            $unit->clients()->detach();
            $unit->lenders()->detach();
        });

    }

    public function appr_type()
    {
        return $this->belongsToMany('App\Models\Customizations\Type','ead_unit_appraisal_type_rel','rel_id','appr_id');
    }
    public function loan_type()
    {
        return $this->belongsToMany('App\Models\Customizations\LoanType','ead_unit_loan_type_rel','rel_id','loan_id');
    }
    public function clients()
    {
        return $this->belongsToMany('App\Models\Clients\Client','ead_unit_client_rel','rel_id','client_id');
    }
    public function lenders()
    {
        return $this->belongsToMany('App\Models\Management\WholesaleLenders\UserGroupLender','ead_unit_lender_rel','rel_id','lender_id');
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
    }

    public static function updateRelations($eadUnit, $data)
    {
        if (!empty($data['appr_type'])) {
            $eadUnit->appr_type()->sync($data['appr_type']);
        } else {
            $eadUnit->appr_type()->detach();
        }
        if (!empty($data['loan_type'])) {
            $eadUnit->loan_type()->sync($data['loan_type']);
        } else {
            $eadUnit->loan_type()->detach();
        }
        if (!empty($data['clients'])) {
            $eadUnit->clients()->sync($data['clients']);
        } else {
            $eadUnit->clients()->detach();
        }
        if (!empty($data['lenders'])) {
            $eadUnit->lenders()->sync($data['lenders']);
        } else {
            $eadUnit->lenders()->detach();
        }
    }
}
