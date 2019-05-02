<?php

namespace App\Models\Appraisal;

use App\Models\BaseModel;
use App\Models\Users\User;
use App\Models\Appraisal\Order;

/**
 * Class OrderAddFee
 * @package App\Models\Appraisal
 */
class OrderAddFee extends BaseModel
{
    protected $table = 'order_add_fees';
    
    public $timestamps = false;

    protected $fillable = [
        'paid'
    ];
    
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'apprid');
    }

    /**
     * Connection to user_data table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userData()
    {
        return $this->hasOne('App\Models\Users\UserData', 'user_id', 'apprid');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'orderid')->with(['apprStatus']);
    }

    /**
     * Connection to user_data table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userDataByAcceptedBy()
    {
        return $this->hasOne('App\Models\Users\UserData', 'user_id', 'apprid');
    }

    /**
     * Connection to user table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userAcceptedBy()
    {
        return $this->hasOne('App\Models\Users\User', 'id', 'apprid');
    }
}
