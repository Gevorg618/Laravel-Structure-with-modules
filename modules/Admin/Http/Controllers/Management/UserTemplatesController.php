<?php

namespace Modules\Admin\Http\Controllers\Management;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Management\UserTemplate;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\Management\UserTemplatesRequest;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Session;

class UserTemplatesController extends AdminBaseController
{
    public function index()
    {
        return view('admin::management.user-templates.index');
    }

    /**
     * Processing datatables ajax
     * @param Request $request
     * @return mixed
     */
    public function userTemplatesData(Request $request)
    {
        if ($request->ajax()) {
            $userTemplates = UserTemplate::has('user')->with('user')->get();
            return Datatables::of($userTemplates)
                ->editColumn('created_at',function($r){
                    return Carbon::createFromTimestamp($r->created_at);
                })
                ->editColumn('user_id', function ($r) {
                    return $r->user->firstname.' '.$r->user->lastname;
                })
                ->editColumn('is_approved', function ($r) {
                    return ($r->is_approved)?'Yes':'No';
                })
                ->addColumn('action', function ($r) {
                    return view('admin::management.user-templates.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }

    /**
     * @param UserTemplatesRequest $request
     * @param UserTemplate $userTemplate
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function createUserTemplate(UserTemplatesRequest $request, UserTemplate $userTemplate)
    {
        if($request->isMethod('post')) {
            $data = $request->all();
            $create = $userTemplate->create($data);
            if ($create) {
                Session::flash('success', 'Email Template Created.');
                return redirect()->route('admin.management.user-templates');
            }
        }

        return view('admin::management.user-templates.create', compact('userTemplate'));
    }

    /**
     * @param UserTemplatesRequest $request
     * @param UserTemplate $userTemplate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUserTemplate(UserTemplatesRequest $request, UserTemplate $userTemplate)
    {
        if ($request->isMethod('put')) {
            $data = $request->all();

            $update = $userTemplate->update($data);
            if ($update) {
                Session::flash('success', 'Email Template Updated.');
            }
        }
        return redirect()->route('admin.management.user-templates');
    }

    /**
     * @param UserTemplate $userTemplate
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function deleteUserTemplate(UserTemplate $userTemplate)
    {
        $userTemplate->delete();
        Session::flash('success', 'Email Template Deleted.');
        return redirect()->route('admin.management.user-templates');
    }
}
