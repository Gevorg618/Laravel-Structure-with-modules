<?php

namespace App\Models\Users;

use App\Models\BaseModel;

class UserData extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_data';

    protected $fillable = [
        'user_id',
        'firstname',
        'lastname',
        'middlename',
        'suffix',
        'title',
        'phone',
        'mobile',
        'fax',
        'phoneext',
        'company',
        'comp_address',
        'comp_address1',
        'comp_city',
        'comp_state',
        'comp_zip',
        'user_notes',
        'email_signature',
        'amc_api_account',
        'amc_api_account_api_id',
        'payable_company',
        'payable_address',
        'payable_address1',
        'payable_city',
        'payable_state',
        'payable_zip',
        'new_construction_expert',
        'payment_email_notification',
        'payment_sms_notification',
        'accept_cod',
        'is_allowed_license_bypass',
        'is_priority_appr',
        'software_charge',
        'is_auto_select_priority',
        'is_in_house',
        'is_zero_fee',
        'appr_state_compliance_approved',
        'exclude',
        'capacity',
        'ein',
        'pos_lat',
        'pos_long',
        'tax_class',
        'ins_company',
        'ins_amt',
        'ins_amt_agg',
        'ins_expire',
        'fha',
        'is_away',
        'daily_digest',
        'away_start_date',
        'away_end_date',
        'phone_type',
        'phone_provider',
        'autoselect_enabled',
        'has_background_check',
        'background_check_date',
        'appr_software',
        'enable_text_invites',
    ];

    public function scopeOfName($query, $name = false)
    {
        if ($name) {
            return $query->where('user_data.firstname', 'like', '%' . $name . '%')
                ->orWhere('user_data.lastname', 'like', '%' . $name . '%');
        }
    }

    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }
}
