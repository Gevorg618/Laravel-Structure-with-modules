<?php

namespace Modules\Admin\Repositories;


use App\Models\UserAscLicense;

class UserAscLicenseRepository
{
    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAppraiserCachedASCLicenses($id)
    {
        return UserAscLicense::where('user_id', $id)->get();
    }
}