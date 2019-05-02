<?php

namespace App\Models\Permissions;

use App\Models\BaseModel;

class UserPermissions extends BaseModel
{
    protected $table = 'user_permission';

    protected $fillable = [
        'user_id',
        'perm_key',
        'value',
    ];

    public $timestamps = false;

    public static $permissions = [];

    public static function getUserPermById($id, $key)
    {
        $permissions = self::getUserPermissions($id);
        // Check if we are a system administrator
        if (isset($permissions['system_admin']) && $permissions['system_admin']) {
            return true;
        }
        return isset($permissions[$key]) ? $permissions[$key] : false;
    }

    public static function getUserPermissions($id)
    {
        if (!isset(self::$permissions[$id])) {
            self::$permissions[$id] = [];

            // Load from database
            $_permissions = self::where('user_id', $id)->get();

            $permissions = [];
            if ($_permissions) {
                foreach ($_permissions as $item) {
                    $permissions[$item->perm_key] = $item->value;
                }
            }
            self::$permissions[$id] = $permissions;
        }
        return self::$permissions[$id];
    }
}
