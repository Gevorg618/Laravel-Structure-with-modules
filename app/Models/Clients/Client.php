<?php

namespace App\Models\Clients;

use App\Models\Users\User;
use App\Models\BaseModel;
use App\Models\Appraisal\Order;
use App\Models\Appraisal\QC\Checklist;
use App\Models\AutoSelectPricing\AutoSelectPricingGroupFee;
use App\Models\Management\AdminTeamsManager\AdminTeamClient;
use App\Models\Appraisal\ApprStatePrice;

/**
 * Class Client
 * @package App\Models\Clients
 */
class Client extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_groups';

    protected $fillable = [
        'active',
        'descrip',
        'user_group_type',
        'company',
        'address1',
        'address2',
        'city',
        'state',
        'zip',
        'corporate_phone',
        'twitter',
        'linkedin',
        'show_apprtype',
        'show_loanpurpose',
        'show_loantype',
        'show_propertytype',
        'allow_amc_submit',
        'allow_amc_submit_individual',
        'estimated_total_monthly_volume',
        'estimated_total_monthly_volume_one',
        'estimated_total_monthly_volume_two',
        'estimated_total_monthly_volume_three',
        'estimated_total_monthly_volume_six',
        'estimated_product_volume_conv',
        'estimated_product_volume_fha',
        'estimated_product_volume_forward',
        'estimated_product_volume_reverse',
        'estimated_product_volume_altval',
        'estimated_start_date',
        'can_place_appraisal_orders',
        'show_valuclear',
        'show_group_as_lender',
        'invoice_show_processor',
        'show_client_order_number',
        'require_client_order_number',
        'is_default_on_hold',
        'disable_appr_on_hold',
        'notify_order_placed_emails',
        'notify_order_placed_subject',
        'notify_order_placed_content',
        'additional_email',
        'final_report_emails',
        'add_email_status',
        'add_email_support',
        'allow_partial_payment',
        'allow_cod_payment',
        'enable_cfb_payment',
        'cod_payment_fee',
        'allow_check_payment',
        'creditcard_custom_price',
        'payment_time',
        'cc_borrower_payment',
        'realview_checklist',
        'appraisal_orders_qc_type',
        'show_mortgage_associate',
        'enable_appr_schedule_dates',
        'show_reqfha',
        'require_purchasecontract',
        'hide_units',
        'show_uwallusers',
        'show_uwmgrusers',
        'auto_req_investdocs',
        'opt_contactentry',
        'req_loannum',
        'show_paylater',
        'show_price',
        'def_occ',
        'def_loantype',
        'def_units',
        'hidelegaldescrip',
        'standard_legal',
        'show_apprtype',
        'show_loantype',
        'show_loanpurpose',
        'show_propertytype',
        'tila_auth',
        'attach_hvcc_cert',
        'attach_hvcc_cert_forfha',
        'master_email_control',
        'supress_inspcomplete',
        'lender_final_email',
        'send_xml_report',
        'emailcontrol_status',
        'emailcontrol_support',
        'emailcontrol_final',
        'admin_notes',
        'warr_addfee',
        'mail_appr_addfee',
        'investment_docs_add',
        'valuclear_discount',
        'pricing_version',
        'enable_docuvault',
        'docuvault_require_payment',
        'docuvault_fee',
        'send_docuvault_download_confirmation',
        'enable_avm',
        'avm_require_payment',
        'avm_fee',
        'auto_select_enabled',
        'auto_select_prefered_only',
        'auto_select_prefered_only_miles',
        'enable_client_survey',
        'final_appr_attach_documents',
        'enable_qc_email_notification',
        'enable_qc_correction_email_notification',
        'support_email_addresses',
        'payment_confirmation_additional_emails',
        'req_cert_appr',
        'req_fha_appr',
        'min_eoins_require_each',
        'min_eoins_require_agg',
        'hide_commentswhenorder',
        'standard_guidelines',
        'lenders_used',
        'net_days',
        'valusync_net_days',
        'enable_auto_ar',
        'auto_ar_emails',
        'ap_company',
        'ap_address1',
        'ap_address2',
        'ap_city',
        'net_days',
        'ap_zip',
        'ap_contact',
        'ap_phone',
        'ap_email',
        'ap_statementemails',
        'cc_name',
        'cc_number',
        'cc_exp',
        'cc_billing_address',
        'cc_billing_address2',
        'cc_billing_city',
        'cc_billing_state',
        'cc_billing_zip',
        'mercury_client_id',
        'mercury_catch_all_user_id',
        'mercury_send_borrower_payment_collection',
        'mercury_auto_charge_credit_card',
        'mercury_enable_email_mail',
        'mercury_check_proceed',
        'mercury_excluded_loan_numbers',
        'valutrac_client_id',
        'valutrac_catch_all_user_id',
        'fnc_client_id',
        'fnc_catch_all_user_id',
        'integration_group_assign_keyword',
        'auto_submit_ucdp',
        'auto_submit_ead',
        'fnc_enable_fee_quote',
        'fnc_high_value_loan_reason',
        'fnc_send_realview',
        'salesid',
        'salesid2',
        'salesid_com',
        'salesid2_com',
        'salesid_alt_com',
        'salesid2_alt_com',
        'manager',
        'software_fee',
        'manager_com',
        'sales_com_deduct_amount',
        'manager_alt_com',
        'has_default_lender'
    ];

    public $timestamps = false;

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function autoSelectPricingGroupFees()
    {
        return $this->hasMany(AutoSelectPricingGroupFee::class, 'group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function checklists()
    {
        return $this->belongsToMany(
            Checklist::class,
            'appr_qc_checklist_client',
            'client_id',
            'rel_id'
        );
    }

    public static function getAllClients()
    {
        return self::select('id', 'descrip')->orderBy('descrip', 'ASC')->get();
    }

    public static function teams($id)
    {
        return self::where('id', $id)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function adminTeamClient()
    {
        return $this->hasOne(AdminTeamClient::class, 'user_group_id')
            ->with('adminTeam');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeInvoiced($query)
    {
        return $query->where('net_days', '!=', 'DNB');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeNonInvoiced($query)
    {
        return $query->where('net_days', 'DNB');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'groupid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'groupid')->with('userData');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activeUsers()
    {
        return $this->users()->where('active', 'Y');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function apprStatePrice()
    {
        return $this->hasMany(ApprStatePrice::class, 'groupid');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function setPricingVersions()
    {
        return $this->apprStatePrice()->where('amount', '>', 0)->where('fha_amount', '>', 0);
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function emptyPricingVersions()
    {
        return $this->apprStatePrice()->where('amount', '<=', 0)->where('fha_amount', '<=', 0);
    }


    /**
     * Get the userGroupFiles for the Client.
     */

    public function userGroupFiles()
    {
        return $this->hasMany('App\Models\Clients\UserGroupFile', 'group_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function apiUsers()
    {
        return $this->belongsToMany('App\Models\Integrations\APIUsers\APIUser', 'api_user_group', 'group_id', 'api_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function preferAppr()
    {
        return $this->hasMany('App\Models\Clients\PreferAppr', 'groupid')->with('userData')->with('user');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function excludeAppr()
    {
        return $this->hasMany('App\Models\ExcludeAppr', 'groupid')->with('userData')->with('user');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userGroupRelations()
    {
        return $this->hasMany('App\Models\Users\UserGroupRelation', 'group_id')->with('userData')->with('user');
    }


}
