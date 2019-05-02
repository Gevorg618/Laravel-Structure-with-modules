<?php

namespace Modules\Admin\Repositories;


use App\Models\UserFhaStateApproved;

class UserFhaStateApprovedRepository
{
    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAppraiserCachedFHALicenses($id)
    {
        return UserFhaStateApproved::where('user_id', $id)->get();
    }

    /**
     * @param $id
     */
    public function deleteByUserId($id)
    {
        UserFhaStateApproved::where('user_id', $id)->delete();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insert($data = [])
    {
        return UserFhaStateApproved::insert($data);
    }
}