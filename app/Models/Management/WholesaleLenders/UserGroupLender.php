<?php

namespace App\Models\Management\WholesaleLenders;

use App\Models\BaseModel;
use App\Models\Appraisal\QC\Checklist;

class UserGroupLender extends BaseModel
{
    protected $table = 'user_group_lender';

    protected $fillable = [
        'lender',
        'lender_dropdown_title',
        'lender_address1',
        'lender_address2',
        'lender_city',
        'lender_state',
        'lender_zip',
        'send_final_report',
        'final_report_emails',
        'final_report_emails_uw',
        'comments',
        'finalborroweremail',
        'finalborrowerpostal',
        'enable_avm',
        'avm_require_payment',
        'avm_fee',
        'enable_docuvault',
        'docuvault_require_payment',
        'docuvault_fee',
        'mail_appr_addfee',
        'admin_notes',
        'enable_auto_select',
        'custom_titles',
        'tila_auth',
        'tila_emails',
        'salesid',
        'salesid2',
        'salesid_com',
        'salesid2_com',
        'salesid_alt_com',
        'salesid2_alt_com',
        'manager',
        'manager_com',
        'manager_alt_com',
        'signup_logo',
        'signup_note',
        'default_watch_list',
        'is_proposed_loan_amount',
        'min_eoins_require_each',
        'min_eoins_require_agg'
    ];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function checklists()
    {
        return $this->belongsToMany(
            Checklist::class,
            'appr_qc_checklist_lender',
            'lender_id',
            'rel_id'
        );
    }

    public static function getLenderRecord($id)
    {
        return self::where('id', $id)->first();
    }
    
    public function profiles()
    {
        return $this->belongsToMany('App\Models\Users\User', 'user_group_lender_exclude_appraiser','lenderid','userid')->withPivot('created_date');
    }
    public function licenses()
    {
        return $this->hasMany('App\Models\Management\WholesaleLenders', 'lender_id');
    }
}
