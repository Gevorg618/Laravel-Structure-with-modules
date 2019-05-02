<?php

namespace Modules\Admin\Repositories;


use App\Models\Management\AdminGroup\AdminGroup;

class AdminGroupRepository
{
    /**
     * @return mixed
     */
    public function getAdminGroupsDropdown()
    {
        return AdminGroup::orderBy('title')->pluck('title', 'id');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getAdminGroupById($id)
    {
        return AdminGroup::find($id);
    }
}