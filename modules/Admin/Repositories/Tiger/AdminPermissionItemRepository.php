<?php

namespace Modules\Admin\Repositories\Tiger;


use App\Models\Management\AdminGroup\AdminPermissionItem;

class AdminPermissionItemRepository
{
    /**
     * @param $groupId
     * @return mixed
     */
    public function getAllByGroup($groupId)
    {
        return AdminPermissionItem::where('group_id', $groupId)
            ->orderBy('title')->get();
    }
}