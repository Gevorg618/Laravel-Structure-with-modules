<?php

namespace Modules\Admin\Repositories;


use App\Models\UserCommunicationPreference;

class UserCommunicationPreferenceRepository
{
    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getSelectedCommunicationMethods($id)
    {
        return UserCommunicationPreference::where('user_id', $id)->pluck('type');
    }

    /**
     * @param $id
     * @return bool|null
     */
    public function deleteByUserId($id)
    {
        return UserCommunicationPreference::where('user_id', $id)->delete();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insert($data = [])
    {
        return UserCommunicationPreference::insert($data);
    }
}