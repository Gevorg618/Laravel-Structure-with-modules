<?php

namespace Modules\Admin\Repositories;


use App\Models\ApprEmailRule;

class ApprEmailRulesRepository
{
    /**
     * @param $userId
     * @return bool|null
     */
    public function deleteByUser($userId)
    {
        return ApprEmailRule::where('user_id', $userId)->delete();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insertByArray($data = [])
    {
        return ApprEmailRule::insert($data);
    }
}