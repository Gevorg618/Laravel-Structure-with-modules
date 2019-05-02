<?php

namespace Modules\Admin\Repositories;


use App\Models\Management\AdminGroup\AdminPermissionGroup;

class AdminPermissionGroupRepository
{
    /**
     * @param $catId
     * @return mixed
     */
    public function getAllByCategory($catId)
    {
        return AdminPermissionGroup::where('category_id', $catId)
            ->orderBy('title')->get();
    }
}