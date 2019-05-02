<?php

namespace App\Models\AlternativeValuation;

use App\Models\BaseModel;
use App\Models\AlternativeValuation\OrderStatus;
use App\Models\AlternativeValuation\Order;
use App\Models\Users\User;
use App\Models\Users\UserData;

/**
 * Class AltSubOrder
 * @package App\Models\Appraisal
 */
class SubOrder extends BaseModel
{
    protected $table = 'alt_sub_order';

    public $timestamps = false;

    public function agent()
    {
        return $this->hasOne(User::class, 'id', 'acceptedby');
    }

    /**
     * Connection to alt_ordertable
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'parent_order_id');
    }
    
    /**
     * Connection to alt_order_status table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'status');
    }

    /**
     * Connection to user_data table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userData()
    {
        return $this->hasOne('App\Models\Users\UserData', 'user_id', 'orderedby');
    }

    /**
     * Agent
     * Connection to user_data table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function agentData()
    {
        return $this->hasOne(UserData::class, 'user_id', 'acceptedby');
    }

    /**
     * Connection to user_data table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userDataByAcceptedBy()
    {
        return $this->agentData();
    }

    /**
     * Connection to user table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userAcceptedBy()
    {
        return $this->agent();
    }
}
