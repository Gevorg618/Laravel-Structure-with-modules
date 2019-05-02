<?php

namespace Modules\Admin\Repositories;


use App\Models\UserPermissionGroup;

class UserPermissionGroupRepository
{
    /**
     * @param $categoryId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function groups($categoryId)
    {
        return UserPermissionGroup::where('category_id', $categoryId)
            ->orderBy('title')->get();
    }
}