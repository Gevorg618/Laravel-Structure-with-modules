<?php

namespace App\Models\Accounting;
use App\Models\BaseModel;

class EmailNotification extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'accounting_payable_vendor_email_notification';
    
    public $timestamps = false;

    protected $fillable = [
        'payable_id',
        'created_date'
    ];

}
