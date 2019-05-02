<?php

namespace Modules\Admin\Repositories;


use App\Models\UserFdProfile;

class UserFdProfileRepository
{
    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getUserLastFirstDataProfile($id)
    {
        return UserFdProfile::where('user_id', $id)
            ->latest('id')->first();
    }
}