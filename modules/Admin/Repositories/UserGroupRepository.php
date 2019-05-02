<?php

namespace Modules\Admin\Repositories;


use App\Models\UserGroup;

class UserGroupRepository
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function getUserGroups()
    {
        return UserGroup::pluck('title', 'id');
    }
}