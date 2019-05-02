<?php

namespace App\Models\Management\AdminGroup;

use App\Models\BaseModel;

class AdminPermissionItem extends BaseModel
{
    protected $connection = 'tiger';

    protected $table = 'admin_permission_item';

    protected $fillable = [
        'key',
        'title',
        'default',
        'group_id',
        'description',
    ];

    public $timestamps = false;

    public function allItems()
    {
        return $this->orderBy('title', 'ASC')->get();
    }
}
