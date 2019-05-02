<?php

namespace Modules\Admin\Repositories;


use App\Models\UserClientPermission;

class UserClientPermissionRepository
{
    /**
     * @param $userId
     * @param $permId
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getOneByUserAndPermission($userId, $permId)
    {
        return UserClientPermission::where('user_id', $userId)
            ->where('permission_id', $permId)->first();
    }

    /**
     * @param $id
     * @return bool|null
     */
    public function deleteByUser($id)
    {
        return UserClientPermission::where('user_id', $id)->delete();
    }

    /**
     * @param $userId
     * @param $permissions
     * @return bool
     */
    public function savePermissions($userId, $permissions) {
        foreach($permissions as $permissionId => $value) {
            $model = UserClientPermission::firstOrNew(['user_id' => $userId, 'permission_id' => $permissionId]);
            $model->value = $value;
            $model->save();
        }
        return true;
    }
}