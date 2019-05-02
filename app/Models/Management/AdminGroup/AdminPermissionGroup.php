<?php

namespace App\Models\Management\AdminGroup;

use App\Models\BaseModel;

class AdminPermissionGroup extends BaseModel
{
    protected $connection = 'tiger';

    protected $table = 'admin_permission_group';

    protected $fillable = [
        'category_id',
        'title',
        'description',
    ];

    public $timestamps = false;

    public function allGroups()
    {
        return $this->orderBy('title', 'ASC')->get();
    }

    public function items()
    {
        return $this->hasMany('App\Models\Management\AdminGroup\AdminPermissionItem', 'group_id');
    }
}
