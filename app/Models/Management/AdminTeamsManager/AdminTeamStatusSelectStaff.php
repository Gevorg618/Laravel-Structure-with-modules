<?php

namespace App\Models\Management\AdminTeamsManager;

use App\Models\BaseModel;

class AdminTeamStatusSelectStaff extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_team_status_select_staff';

    protected $fillable = [
        'team_id',
        'user_id'
    ];

    public $timestamps = false;

    public static function getSelectedStaff($id)
    {
        return self::select('user_id')->where('team_id', $id)->get();
    }
}
