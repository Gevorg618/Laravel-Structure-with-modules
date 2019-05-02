<?php

namespace App\Models\Management\AdminGroup;

use App\Models\BaseModel;

class AdminPermissionCategory extends BaseModel
{
    protected $connection = 'tiger';

    protected $table = 'admin_permission_category';

    protected $fillable = [
        'key',
        'title',
        'description',
    ];

    public $timestamps = false;

    public function allCategories()
    {
        return $this->orderBy('title', 'ASC')->get();
    }

    public function groups()
    {
        return $this->hasMany('App\Models\Management\AdminGroup\AdminPermissionGroup', 'category_id');
    }

    /**
     * @return array
     */
    public static function getAdminGroupPermissions()
    {
        $categories = self::orderBy('title', 'asc')->get();
        $rows = [];

        foreach ($categories as $category) {
            $rows[$category->key] = [
                'title' => $category->title,
                'groups' => []
            ];

            $groups = AdminPermissionGroup::where('category_id', $category->id)->orderBy('title', 'asc') ->get();

            if($groups) {
                foreach ($groups as $group) {
                    $rows[$category->key]['groups'][$group->id] = ['header' => $group->title, 'items' => []];

                    $items = AdminPermissionItem::where('group_id', $group->id)->orderBy('title', 'asc')->get();
                    if($items) {
                        foreach ($items as $item) {
                            $rows[$category->key]['groups'][$group->id]['items'][] = ['key' => $item->key, 'title' => $item->title, 'default' => $item->default];
                        }
                    }
                }
            }
        }

        return $rows;
    }
}
