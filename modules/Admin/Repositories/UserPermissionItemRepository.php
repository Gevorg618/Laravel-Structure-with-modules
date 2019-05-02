<?php

namespace Modules\Admin\Repositories;


use App\Models\UserPermissionItem;

class UserPermissionItemRepository
{
    /**
     * @param $groupId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function items($groupId)
    {
        return UserPermissionItem::where('group_id', $groupId)
            ->orderBy('title')->get();
    }

    /**
     * @param $idOrKey
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function item($idOrKey)
    {
        return UserPermissionItem::where('id', $idOrKey)
            ->orWhere('key', $idOrKey)->first();
    }
}