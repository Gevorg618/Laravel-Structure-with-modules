<?php

namespace Modules\Admin\Repositories;


use App\Models\Users\SupervisingUser;

class SupervisingUsersRepository
{
    /**
     * @param $userId
     * @param $items
     * @return bool
     */
    public function save($userId, $items)
    {
        SupervisingUser::where('user_id', $userId)->delete();
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'user_id' => $userId,
                'subordinate_id' => $item,
            ];
        }
        return SupervisingUser::insert($data);
    }
}
