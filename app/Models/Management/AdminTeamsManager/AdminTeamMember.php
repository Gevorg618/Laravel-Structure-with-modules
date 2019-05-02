<?php

namespace App\Models\Management\AdminTeamsManager;

use App\Models\BaseModel;

class AdminTeamMember extends BaseModel
{
    protected $table = 'admin_team_member';

    protected $fillable = [
        'team_id',
        'user_id'
    ];

    public $timestamps = false;

    public static function getSelectedMembers($id)
    {
        return self::select('user_id')->where('team_id', $id)->get();
    }

    public static function getAdminUserTeamId($id)
    {
        $row = self::where('user_id', $id)->first();
        return $row ? $row->team_id : false;
    }

}
