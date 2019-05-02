<?php

namespace App\Models\Accounting;

use App\Models\BaseModel;
use App\Models\{AccountingPayablePayment, User, UserData};
use App\Models\Appraisal\{AppraiserPayment, Order};

/**
 * Class AccountingPayablePaymentRecord
 * @package App\Models
 */
class AccountingPayablePaymentRecord extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'accounting_payable_payment_record';

    public $timestamps = false;


    protected $fillable = [
        'payable_payment_id',
        'uid',
        'name',
        'address',
        'city',
        'state',
        'zip',
        'check_number',
        'check_amount',
        'pay_date',
        'date_delivered',
        'orderid',
        'prop_address',
        'split'
    ];


    /**
     * @param $query
     * @param $id
     * @return mixed
     */
    public function scopeGetByPaymentId($query, $id)
    {
        return $query->where('payable_payment_id', $id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payablePayment()
    {
        return $this->belongsTo(AccountingPayablePayment::class, 'payable_payment_id')->with(['user']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userData()
    {
        return $this->belongsTo(UserData::class, 'uid' , 'user_id' );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function appraiserPayment()
    {
        return $this->belongsTo(AppraiserPayment::class, 'orderid' , 'orderid' );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function apprOrder()
    {
        return $this->belongsTo(Order::class, 'orderid');
    }

}
