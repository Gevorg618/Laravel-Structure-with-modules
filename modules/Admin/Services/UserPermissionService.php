<?php

namespace Modules\Admin\Services;


use Modules\Admin\Repositories\UserClientPermissionRepository;
use Modules\Admin\Repositories\UserPermissionCategoryRepository;
use Modules\Admin\Repositories\UserPermissionGroupRepository;
use Modules\Admin\Repositories\UserPermissionItemRepository;

class UserPermissionService
{
    protected $userPermissionCategoryRepo;
    protected $userPermissionGroupRepo;
    protected $userPermissionItemRepo;
    protected $userClientPermissionRepo;

    /**
     * UserPermissionService constructor.
     * @param $userPermissionCategoryRepo
     */
    public function __construct(
        UserPermissionCategoryRepository $userPermissionCategoryRepo,
        UserPermissionGroupRepository $userPermissionGroupRepo,
        UserPermissionItemRepository $userPermissionItemRepo,
        UserClientPermissionRepository $userClientPermissionRepo
    )
    {
        $this->userPermissionCategoryRepo = $userPermissionCategoryRepo;
        $this->userPermissionGroupRepo = $userPermissionGroupRepo;
        $this->userPermissionItemRepo = $userPermissionItemRepo;
        $this->userClientPermissionRepo = $userClientPermissionRepo;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function categories()
    {
        return $this->userPermissionCategoryRepo->categories();
    }

    /**
     * @param $categoryId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function groups($categoryId)
    {
        return $this->userPermissionGroupRepo->groups($categoryId);
    }

    /**
     * @param $groupId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function items($groupId)
    {
        return $this->userPermissionItemRepo->items($groupId);
    }

    /**
     * @param $idOrKey
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function item($idOrKey)
    {
        return $this->userPermissionItemRepo->item($idOrKey);
    }

    /**
     * @param $userId
     * @param $key
     * @return bool|mixed
     */
    public function can($userId, $key)
    {
        $row = $this->item($key);
        if(!$row) {
            return false;
        }

        $perm = $this->userClientPermissionRepo->getOneByUserAndPermission($userId, $row->id);

        if(!$perm) {
            return $row->default;
        }

        return $perm->value;
    }
}