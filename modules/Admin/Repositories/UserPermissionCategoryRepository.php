<?php

namespace Modules\Admin\Repositories;


use App\Models\UserPermissionCategory;

class UserPermissionCategoryRepository
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function categories()
    {
        return UserPermissionCategory::orderBy('title')->get();
    }
}