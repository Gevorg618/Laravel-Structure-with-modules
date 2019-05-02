<?php

namespace Modules\Admin\Repositories\Tiger;


use App\Models\Management\AdminGroup\AdminPermissionCategory;

class AdminPermissionCategoryRepository
{
    /**
     * @return mixed
     */
    public function getAll()
    {
        return AdminPermissionCategory::orderBy('title')->get();
    }
}