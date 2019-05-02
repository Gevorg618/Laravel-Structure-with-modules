<?php

namespace App\Models\Management;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomEmailTemplate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'custom_email';
    protected $fillable = ['title','email_key','created_by','content','software_fee','created_date','last_updated_date'];
    protected $with = ['appr_type','loan_type','states','clients','lenders'];
    public $timestamps = false;
    public static function boot() {
        parent::boot();

        static::deleting(function($customEmailTemplate) { // before delete() method call this
            $customEmailTemplate->appr_type()->detach();
            $customEmailTemplate->loan_type()->detach();
            $customEmailTemplate->states()->detach();
            $customEmailTemplate->clients()->detach();
            $customEmailTemplate->lenders()->detach();
        });

    }
    public function appr_type()
    {
        return $this->belongsToMany('App\Models\Customizations\Type','custom_email_appr_type','custom_email_id','appr_type_id');
    }
    public function loan_type()
    {
        return $this->belongsToMany('App\Models\Customizations\LoanType','custom_email_loan_type','custom_email_id','loan_type_id');
    }
    public function states()
    {
        return $this->belongsToMany('App\Models\Geo\State','custom_email_state','email_id','state_id');
    }
    public function clients()
    {
        return $this->belongsToMany('App\Models\Clients\Client','custom_email_client','email_id','client_id');
    }
    public function lenders()
    {
        return $this->belongsToMany('App\Models\Management\WholesaleLenders\UserGroupLender','custom_email_lender','email_id','lender_id');
    }
    public static function saveRelations($create,$data)
    {
        if (!empty($data['appr_type'])) {
            $create->appr_type()->attach($data['appr_type']);
        }
        if (!empty($data['loan_type'])) {
            $create->loan_type()->attach($data['loan_type']);
        }
        if (!empty($data['states'])) {
            $create->states()->attach($data['states']);
        }
        if (!empty($data['clients'])) {
            $create->clients()->attach($data['clients']);
        }
        if (!empty($data['lenders'])) {
            $create->lenders()->attach($data['lenders']);
        }
    }
    public static function updateRelations($customEmailTemplate, $data)
    {
        if (!empty($data['appr_type'])) {
            $customEmailTemplate->appr_type()->sync($data['appr_type']);
        } else {
            $customEmailTemplate->appr_type()->detach();
        }
        if (!empty($data['loan_type'])) {
            $customEmailTemplate->loan_type()->sync($data['loan_type']);
        } else {
            $customEmailTemplate->loan_type()->detach();
        }
        if (!empty($data['states'])) {
            $customEmailTemplate->states()->sync($data['states']);
        } else {
            $customEmailTemplate->states()->detach();
        }
        if (!empty($data['clients'])) {
            $customEmailTemplate->clients()->sync($data['clients']);
        } else {
            $customEmailTemplate->clients()->detach();
        }
        if (!empty($data['lenders'])) {
            $customEmailTemplate->lenders()->sync($data['lenders']);
        } else {
            $customEmailTemplate->lenders()->detach();
        }
    }
}
