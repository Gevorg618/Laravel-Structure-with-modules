<?php

namespace Modules\Admin\Repositories;


use App\Models\PreferAppr;

class PreferApprRepository
{
    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]\
     */
    public function getAppraiserPreferredGroups($userId)
    {
        return PreferAppr::where('apprid', $userId)->get();
    }
}