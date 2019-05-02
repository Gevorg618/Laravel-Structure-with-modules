<?php

namespace Modules\Admin\Repositories;


use App\Models\Management\AdminTeamsManager\AdminTeam;

class AdminTeamsRepository
{
    /**
     * @param $key
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getTeamByKey($key)
    {
        return AdminTeam::where('team_key', $key)->first();
    }
}