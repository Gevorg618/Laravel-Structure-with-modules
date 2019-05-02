<?php

namespace App\Models\DocuVault;

use App\Models\BaseModel;
use App\Models\Users\User;
use App\Models\DocuVault\Notification;

class Order extends BaseModel
{
    protected $table = 'appr_docuvault_order';

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'orderedby')->with([
            'userData',
            'group'
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function notification()
    {
        return $this->hasOne(Notification::class, 'order_id', 'id');
    }

    /**
     * @return string
     */
    public function getAddressAttribute()
    {
        return \App\Helpers\Address::getFullAddress(
            $this->propaddress1, $this->propaddress2, $this->propcity, $this->propstate, $this->propzip
        );
    }
    
    /*
     * Connection to user_groups table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function groupData()
    {
        return $this->belongsTo('App\Models\Clients\Client', 'groupid')
            ->with('adminTeamClient');
    }

    /**
     * Connection to User table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Users\User', 'orderedby');
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
}
