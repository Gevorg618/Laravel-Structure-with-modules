<?php

namespace App\Models\Management\AdminTeamsManager;

use App\Models\BaseModel;

class AdminTeamStates extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_team_state';

    protected $fillable = [
        'team_id',
        'state'
    ];

    public $timestamps = false;

    public static function getSelectedStates($id)
    {
        return self::select('state')->where('team_id', $id)->get();
    }
}
