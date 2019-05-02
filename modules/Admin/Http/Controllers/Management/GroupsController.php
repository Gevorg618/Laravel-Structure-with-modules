<?php
    
namespace Modules\Admin\Http\Controllers\Management;

use App\Models\Management\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\Management\GroupRequest;
use Yajra\DataTables\Facades\Datatables;


class GroupsController extends AdminBaseController
{
    /**
     * Index page for Appraisal
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::management.groups.index');
    }
    
    /**
     * Gather data for index page and datatables
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            $groups = Group::all();
            
            return Datatables::of($groups)
                ->editColumn('is_protected', function ($r) {
                    return ($r->is_protected) ? 'Yes' : 'No';
                })
                ->editColumn('is_default', function ($r) {
                    return ($r->is_default) ? 'Yes' : 'No';
                })
                ->addColumn('action', function ($r) {
                    return view('admin::management.groups.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }
    
    /**
     * Create new appraisal
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin::management.groups.create');
    }
    
    
    public function store(GroupRequest $request, Group $group)
    {
        $group->store($request);
        Session::flash('success', 'User Group Type Successfully Created.');
        
        return redirect()->route('admin.management.groups.index');
    }
    
    /**
     * Edit property type details
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $group = Group::findOrFail($id);
        
        return view('admin::management.groups.edit', compact('group'));
    }
    
    /**
     * Update appraisal type
     *
     * @param GroupRequest $request
     * @param Group $group
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(GroupRequest $request, Group $group)
    {
        $group->store($request);
        Session::flash('success', 'User Group Successfully Updated.');
        
        return redirect()->route('admin.management.groups.index');
    }
    
    /**
     * Delete User Group type
     *
     * @param Group $group
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Group $group)
    {
        if ($group->isProtected()) {
            Session::flash('error', 'You cannot delete protected user group.');
        
            return redirect()->route('admin.management.groups.index');
        }
        
        Session::flash('success', 'User Group is deleted.');
        $group->delete();
        
        return redirect()->route('admin.management.groups.index');
    }
}
