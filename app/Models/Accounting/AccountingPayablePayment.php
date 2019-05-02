<?php

namespace App\Models\Accounting;

use App\Models\Users\User;

/**
 * Class AccountingPayablePayment
 * @package App\Models
 */
class AccountingPayablePayment extends \Eloquent
{
    /**
     * @var string
     */
    protected $table = 'accounting_payable_payment';
    
    public $timestamps = false;

    protected $fillable = [
        'created_by',
        'created_date'
    ];


    /**
     * @return $this
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by')->with('userData');
    }
}
