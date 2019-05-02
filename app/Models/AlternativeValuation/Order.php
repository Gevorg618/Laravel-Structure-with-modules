<?php

namespace App\Models\AlternativeValuation;

use App\Models\BaseModel;
use App\Models\Clients\Client;
use App\Models\Users\User;
use App\Models\Users\UserData;
use App\Models\AlternativeValuation\OrderStatus;

class Order extends BaseModel
{
    const STATUS_COMPLETE = 8;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alt_order';
    
    public $timestamps = false;

    protected $fillable = [
        'agent_paid'
    ];

    public function getAddressAttribute()
    {
        return \App\Helpers\Address::getFullAddress(
            $this->propaddress1, $this->propaddress2, $this->propcity, $this->propstate, $this->propzip
        );
    }

    /**
     * @return string
     */
    public function getFormatOrderDateAttribute()
    {
        return date('m/d/y', strtotime($this->ordereddate));
    }

    /**
     * @return string
     */
    public function getShortAddressAttribute()
    {
        return trim($this->propaddress1 . ', ' . $this->propcity);
    }

    /**
     * @return string|null
     */
    public function getStatusNameAttribute()
    {
        return $this->orderStatus ? $this->orderStatus->descrip : null;
    }

    /**
     * @return null|string
     */
    public function getProductAttribute()
    {
        return $this->appraisalType
            ? $this->appraisalType->form . ' ' . $this->appraisalType->short_descrip
            : null;
    }

    /**
     * @param $query
     * @param bool|string $search
     * @return mixed
     */
    public function scopeOfSearch($query, $search = false)
    {
        if ($search) {
            return $query->where('alt_order.propaddress1', 'like', '%' . $search . '%')
                ->orWhere('alt_order.propcity', 'like', '%' . $search . '%')
                ->orWhere('alt_order.propstate', 'like', '%' . $search . '%')
                ->orWhere('alt_order.propzip', 'like', '%' . $search . '%')
                ->orWhere('alt_order.id', 'like', '%' . $search);
        }
    }

    /**
     * Connection to order_status table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'status');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'orderedby');
    }

    /**
     * Connection to user_groups table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function groupData()
    {
        return $this->belongsTo(Client::class, 'groupid');
    }

    /**
     * @return string|null
     */
    public function getGroupNameAttribute()
    {
        return $this->groupData ? $this->groupData->descrip : null;
    }

    /**
     * Connection to admin_team_client table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function adminTeamClient()
    {
        return $this->hasOne('App\Models\Management\AdminTeamsManager\AdminTeamClient', 'user_group_id', 'groupid')->with('adminTeam');
    }

    /**
     * Client
     * Connection to user_data table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userData()
    {
        return $this->hasOne(UserData::class, 'user_id', 'orderedby');
    }

    /**
     * Agent
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'acceptedby');
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
        return $this->hasOne('App\Models\Users\UserData', 'user_id', 'acceptedby');
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
