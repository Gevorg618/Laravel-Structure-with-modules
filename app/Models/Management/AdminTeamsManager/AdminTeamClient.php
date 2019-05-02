<?php

namespace App\Models\Management\AdminTeamsManager;

use App\Models\BaseModel;

class AdminTeamClient extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_team_client';

    protected $fillable = [
        'team_id',
        'user_group_id'
    ];

    public $timestamps = false;

    public function adminTeam()
    {
        return $this->belongsTo('App\Models\Management\AdminTeamsManager\AdminTeam', 'team_id');
    }

    public static function getSelectedClients($id)
    {
        return self::select('user_group_id')->where('team_id', $id)->get();
    }
}
