<?php

use App\Models\Users\User;
use App\Models\Management\AdminGroup\AdminGroupPermission;
use App\Models\Permissions\UserPermissions;
use App\Models\Permissions\ClientPermissions;
use App\Models\Management\AdminGroup\AdminPermissionCategory;
use Illuminate\Support\Facades\Cache;

function checkPermission($adminPermissionCategory, $key)
{
    $userId = GetUserId();

    $user = User::where('id', $userId)->with('userData')->first();

    $userAdminGroup = $user->admin_group;

    $groupPermission = AdminGroupPermission::getGroupPermById($userAdminGroup, $key);

    $userPermission = UserPermissions::getUserPermById($userId, $key);

    $access = false;

    $clientPermission = ClientPermissions::getClientPermission($userId, $key);


    if ($clientPermission !==null && !$clientPermission) {
        return $access;
    }

    if ($groupPermission !== false) {
        $access = $groupPermission;
    } else {
        $access = getAdminDefaultValue($key, $adminPermissionCategory);
    }


    if ($userPermission !== false) {
        $access = $userPermission;
    } else {
        if ($access===false) {
            $access = getAdminDefaultValue($key, $adminPermissionCategory);
        }
    }

    return (bool) $access;
}

function checkPerm($key)
{
    $adminPermissionCategory = new AdminPermissionCategory;
    return checkPermission($adminPermissionCategory, $key);
}

function getAdminDefaultValue($key, $adminPermissionCategory)
{
    $perms = getAdminGroupPermissions($adminPermissionCategory);
    $match = false;
    foreach ($perms as $perm) {
        if ($perm['groups'] && count($perm['groups'])) {
            foreach ($perm['groups'] as $group) {
                if ($group['items'] && count($group['items'])) {
                    foreach ($group['items'] as $item) {
                        if ($item['key'] == $key) {
                            return $item['default'];
                        }
                    }
                }
            }
        }
    }
    return $match;
}


function getAdminGroupPermissions($adminPermissionCategory)
{
    $cacheKey = sprintf('admin_group_permissions');
    $value = Cache::get($cacheKey);

    if ($value) {
        return $value;
    }
    $rows = getTigerAdminGroupPermissions($adminPermissionCategory);
    // Store in cache
    Cache::add($cacheKey, $rows, 60*60*24);
    return $rows;
}

function getTigerAdminGroupPermissions($adminPermissionCategory)
{
    $rows = [];
    $categories = $adminPermissionCategory->allCategories();
    foreach ($categories as $category) {
        $rows[$category->key] = [
            'title' => $category->title,
            'groups' => [],
        ];

        $groups = $category->groups;
        if ($groups) {
            foreach ($groups as $group) {
                $rows[$category->key]['groups'][$group->id] = ['header' => $group->title, 'items' => []];

                $items = $group->items;
                if ($items) {
                    foreach ($items as $item) {
                        $rows[$category->key]['groups'][$group->id]['items'][] = ['key' => $item->key, 'title' => $item->title, 'default' => $item->default];
                    }
                }
            }
        }
    }
    return $rows;
}
