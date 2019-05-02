<?php

namespace App\Models\Management\AdminTeamsManager;

use App\Models\BaseModel;

class AdminTeamStatusSelectStatus extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_team_status_select_status';

    protected $fillable = [
        'team_id',
        'status_id'
    ];

    public $timestamps = false;

    public static function getSelectedStatuses($id)
    {
        return self::select('status_id')->where('team_id', $id)->get();
    }
}
