<?php

namespace App\Models\Management\AdminTeamsManager;

use App\Models\BaseModel;

class AdminTeamStatusSelectFlag extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_team_status_select_flag';

    protected $fillable = [
        'team_id',
        'flag_key'
    ];

    public $timestamps = false;

    public static function getSelectedFlags($id)
    {
        return self::select('flag_key')->where('team_id', $id)->get();
    }
}
