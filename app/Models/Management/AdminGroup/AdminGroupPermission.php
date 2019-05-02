<?php

namespace App\Models\Management\AdminGroup;

use App\Models\BaseModel;

class AdminGroupPermission extends BaseModel
{
    protected $table = 'group_permission';

    protected $fillable = [
        'group_id',
        'perm_key',
        'value',
    ];

    public $timestamps = false;

    public static $permissions = [];

    public static function getGroupPermById($id, $key)
    {
        $permissions = self::getUserPermissions($id);
        // Check if we are a system administrator
        if(isset($permissions['system_admin']) && $permissions['system_admin']) {
            return true;
        }
        return isset($permissions[$key]) ? $permissions[$key] : false;
    }

    public static function getUserPermissions($id) {
        if(!isset(self::$permissions[$id])) {
            self::$permissions[$id] = [];

            // Load from database
            $_permissions = self::where('group_id', $id)->get();

            $permissions = [];
            if($_permissions) {
                foreach($_permissions as $item) {
                    $permissions[$item->perm_key] = $item->value;
                }
            }
            self::$permissions[$id] = $permissions;
        }
        return self::$permissions[$id];
    }

}
