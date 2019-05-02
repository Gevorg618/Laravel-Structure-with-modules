<?php

namespace Modules\Admin\Repositories;


use App\Models\UserApprType;

class UserApprTypeRepository
{
    /**
     * @param $userId
     * @return \Illuminate\Support\Collection
     */
    public function getUserSelectedApprTypes($userId)
    {
        return UserApprType::where('user_id', $userId)
            ->pluck('appr_type_id', 'appr_type_id');
    }

    /**
     * @param $userId
     * @return bool|null
     */
    public function deleteByUser($userId)
    {
        return UserApprType::where('user_id', $userId)->delete();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insertByArray($data = [])
    {
        return UserApprType::insert($data);
    }
}