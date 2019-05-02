<?php

namespace App\Models\Traits;

use Schema;

trait ModifiedUser
{
    /**
     * Boot trait
     *
     * @return void
     */
    public static function bootModifiedUser()
    {
        static::creating(function ($row) {
            $row->created_by = static::getUserId();
            if (Schema::hasColumn($row->table, 'updated_by')) {
                $row->updated_by = static::getUserId();
            }
        });

        static::updating(function ($row) {
            if (Schema::hasColumn($row->table, 'updated_by')) {
                $row->updated_by = static::getUserId();
            }
        });

        static::deleting(function ($row) {
            if (Schema::hasColumn($row->table, 'deleted_by')) {
                $row->deleted_by = static::getUserId();
            }
        });
    }

    /**
     * Return the user id if we are an admin or a logged in user
     * @return int
     */
    protected static function getUserId()
    {
        if (admin()) {
            return admin()->id;
        } elseif (user()) {
            return user()->id;
        }

        return 0;
    }
}
