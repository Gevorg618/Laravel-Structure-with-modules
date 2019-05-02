<?php

namespace App\Models\Permissions;

use App\Models\BaseModel;

class ClientPermissions extends BaseModel
{
    protected $connection = 'tiger';

    protected $table = 'client_permissions';

    protected $fillable = [
        'client_id',
        'key',
        'value',
    ];

    protected static $clientPermissions = null;

    public static function getClientPermission($id, $key)
    {
        self::getClientPermissions($id);
        return isset(self::$clientPermissions[$key]) ? self::$clientPermissions[$key] : null;
    }

    public static function getClientPermissions($id)
    {
        $permissions = [];
        $rows = self::where('client_id', $id)->get();
        if ($rows) {
            foreach ($rows as $row) {
                $permissions[$row->key] = $row->value;
            }
        }
        self::$clientPermissions = $permissions;
        return self::$clientPermissions;
    }
}
