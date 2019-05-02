<?php

namespace App\Models\Appraisal;

use App\Models\BaseModel;
use App\Models\Users\User;

/**
 * Class AppraiserPayment
 * @package App\Models\Appraisal
 */
class AppraiserPayment extends BaseModel
{   

    public $timestamps = false;

    protected $table = 'appraiser_payments';

    protected $fillable = [
        'orderid',
        'apprid',
        'paid',
        'paidby',
        'checknum',
        'checkamount',
        'date_sent',
    ];

    /**
     * @return $this
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'apprid')->with('userData');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'orderid');
    }
}
