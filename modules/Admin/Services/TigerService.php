<?php

namespace Modules\Admin\Services;


use App\Models\Tiger\Client;
use Modules\Admin\Repositories\AdminPermissionGroupRepository;
use Modules\Admin\Repositories\Tiger\AdminPermissionCategoryRepository;
use Modules\Admin\Repositories\Tiger\AdminPermissionItemRepository;

class TigerService
{
    protected $adminPermissionCategoryRepo;
    protected $adminPermissionGroupRepo;
    protected $adminPermissionItemRepo;

    /**
     * TigerService constructor.
     * @param $adminPermissionCategoryRepo
     */
    public function __construct(
        AdminPermissionCategoryRepository $adminPermissionCategoryRepo,
        AdminPermissionGroupRepository $adminPermissionGroupRepo,
        AdminPermissionItemRepository $adminPermissionItemRepo
    )
    {
        $this->adminPermissionCategoryRepo = $adminPermissionCategoryRepo;
        $this->adminPermissionGroupRepo = $adminPermissionGroupRepo;
        $this->adminPermissionItemRepo = $adminPermissionItemRepo;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getClient()
    {
        return Client::where('domain', env('APP_URL'))->first();
    }

    /**
     * @return array
     */
    public function getAdminGroupPermissions()
    {
        $categories = $this->adminPermissionCategoryRepo->getAll();
        $rows = [];
        foreach($categories as $category) {
            $rows[$category->key] = [
                'title' => $category->title,
                'groups' => [],
            ];

            $groups = $this->adminPermissionGroupRepo->getAllByCategory($category->id);
            if($groups) {
                foreach($groups as $group) {
                    $rows[$category->key]['groups'][$group->id] = ['header' => $group->title, 'items' => []];

                    $items = $this->adminPermissionItemRepo->getAllByGroup($group->id);
                    if($items) {
                        foreach($items as $item) {
                            $rows[$category->key]['groups'][$group->id]['items'][] = ['key' => $item->key, 'title' => $item->title, 'default' => $item->default];
                        }
                    }

                }
            }
        }

        return $rows;
    }
}
