<?php

namespace App\Models\Customizations;

use App\Models\BaseModel;

class Status extends BaseModel
{   
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'order_status';

    /**
     * We don't use saved and updated timestamps
     *
     * @var bool
     */
    public $timestamps = false;
    
    protected $fillable = ['descrip', 'is_protected', 'status_select_order', 'client_title', 'appraiser_title', 'client_message', 'appraiser_message', 'block_appraiser_actions', 'admin_message', 'vendor_auto_email_enable', 'vendor_auto_email_revisit_date', 'vendor_auto_email_once_every_days', 'vendor_auto_email_text', 'client_auto_email_enable', 'client_auto_email_revisit_date', 'client_auto_email_once_every_days', 'client_auto_email_text', 'vendor_auto_email_subject', 'client_auto_email_subject', 'vendor_auto_email_min_days_in_status', 'client_auto_email_min_days_in_status'];

     
    public function allStatuses()
    {
        return $this->orderBy('descrip', 'ASC')->get();
    }

    public static function getStatuses()
    {
        return self::select('id', 'descrip')->orderBy('descrip', 'ASC')->get();
    }
}
