<?php

namespace Modules\Admin\Http\Controllers\Management;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Cache;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Carbon\Carbon, Session, Input, Html, DB, Validator, Auth, Response, Exception;
use App\Models\Management\AdminGroup\{ AdminGroup, AdminPermissionCategory, AdminPermissionGroup, AdminPermissionItem, AdminGroupPermission };
use Modules\Admin\Http\Requests\Management\AdminGroupsRequest;

class AdminGroupController extends AdminBaseController {

    /**
    * Index
    * @return view
    */
    public function index()
    {
        return view('admin::management.admin_groups.index');
    }

    /**
    * Create
    * @return view
    */
    public function create()
    {
        return view('admin::management.admin_groups.create');
    }

    /**
    * Store
    * @param $request
    * @return view
    */
    public function store(AdminGroupsRequest $request)
    {
        $inputs = $request->all();
        AdminGroup::create([
            'title' => $inputs['title'],
            'color' => $inputs['color'],
            'style' => $inputs['style']
        ]);
        Session::flash('success', 'Successfully Created!');
        return redirect(route('admin.management.admin-groups'));
    }

    /**
    * Edit
    * @param $id
    * @return view
    */
    public function edit(
            $id,
            AdminPermissionCategory $category
        )
    {
        $adminGroup = AdminGroup::where('id', $id)->first();
        $savedGroups = $this->getSavedAdminGroups();
        $categories = $this->getCategories($category);

        return view('admin::management.admin_groups.edit',
            compact(
                'adminGroup',
                'categories',
                'savedGroups'
                )
            );
    }

    /**
    * Get all Saved Admin Groups and add to Cache
    * @return all saved admin groups
    */
    private function getSavedAdminGroups()
    {
        $savedAdminGroupPermissions = sprintf('saved_admin_group_permissions');
        $value = Cache::get($savedAdminGroupPermissions);

        if($value) {
            return $value;
        }

        $savedGroups = AdminGroupPermission::all();
        Cache::add($savedAdminGroupPermissions, $savedGroups, 60*60*24);
        return $savedGroups;
    }

    /**
    * Get all Categories and add to Cache
    * @param $category
    * @return all categories
    */
    private function getCategories($category)
    {
        $adminGroupPermissions = sprintf('admin_group_permissions');
        $value = Cache::get($adminGroupPermissions);

        if($value) {
            return $value;
        }

        $categories = $category->allCategories();
        Cache::add($adminGroupPermissions, $categories, 60*60*24);
        return $categories;
    }

    /**
    * Update
    * @param $id
    * @return void
    */
    public function update(
            $id,
            Request $request,
            AdminPermissionCategory $category
        )
    {
        $inputs = $request->all();
        isset($inputs['permissions']) ? $this->updateAdminGroupsPermissions($inputs['permissions']) : $this->updateAdminGroups($id, $inputs);
        $this->updateCache($category);
        Session::flash('success', 'Successfully Updated!');
        return redirect()->back();
    }

    /**
    * Update Cache
    * @param $category
    * @return void
    */
    private function updateCache($category)
    {
        $adminGroupPermissions = sprintf('admin_group_permissions');
        $savedAdminGroupPermissions = sprintf('saved_admin_group_permissions');

        Cache::forget($adminGroupPermissions);
        Cache::forget($savedAdminGroupPermissions);

        $categories = $category->allCategories();
        Cache::add($adminGroupPermissions, $categories, 60*60*24);

        $savedGroups = AdminGroupPermission::all();
        Cache::add($savedAdminGroupPermissions, $savedGroups, 60*60*24);
    }

    /**
    * Update Admin Groups
    * @param $id, $inputs
    * @return void
    */
    private function updateAdminGroups($id, $inputs)
    {
        AdminGroup::where('id', $id)->update([
            'title' => $inputs['title'],
            'color' => $inputs['color'],
            'style' => $inputs['style']
        ]);
    }

    /**
    * Update Admin Groups Permissions
    * @param $permissions
    * @return void
    */
    private function updateAdminGroupsPermissions($permissions)
    {
        $groupId = array_keys($permissions)[0];
        foreach ($permissions[$groupId] as $key => $value) {
            $perm = AdminGroupPermission::where(['group_id' => $groupId, 'perm_key' => $key])->first();
            if (is_null($perm)) {
                AdminGroupPermission::create([
                    'group_id' => $groupId,
                    'perm_key' => $key,
                    'value' => $value
                ]);
            } else {
                AdminGroupPermission::where(['group_id' => $groupId, 'perm_key' => $key])->update(['value' => $value]);
            }
        }
    }

    /**
    * Delete
    * @param $id
    * @return void
    */
    public function destroy(Request $request)
    {
        $id = $request->id;
        AdminGroup::where('id', $id)->delete();
        AdminGroupPermission::where('group_id', $id)->delete();
        Session::flash('success', 'Successfully Deleted!');
        return redirect()->back();
    }

    /**
    * Clear Cache
    * @return void
    */
    public function clearCache()
    {
        $adminGroupPermissions = sprintf('admin_group_permissions');
        $savedAdminGroupPermissions = sprintf('saved_admin_group_permissions');

        if (Cache::forget($adminGroupPermissions) && Cache::forget($savedAdminGroupPermissions)) {
            Session::flash('success', 'Cache Successfully Cleared!');
            return redirect()->back();
        } else {
            Session::flash('info', 'Cache is empty!');
            return redirect()->back();
        }
    }

    /**
    * data
    * @param $adminGroup
    * @return mixed
    */
    public function data(AdminGroup $adminGroup)
    {
        $groups = $adminGroup->allGroups();
        return Datatables::of($groups)
            ->editColumn('is_protected', function($r) {
                return $r->is_protected ? 'Yes' : 'No';
            })
            ->addColumn('action', function ($r) {
                return view('admin::management.admin_groups.partials._options', ['row' => $r]);
            })
            ->make(true);
    }
}
