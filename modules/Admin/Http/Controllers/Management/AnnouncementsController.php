<?php

namespace Modules\Admin\Http\Controllers\Management;

use App\Models\Management\Announcement;
use App\Models\Management\UserType;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\Management\AnnouncementRequest;
use Yajra\DataTables\Datatables;

class AnnouncementsController extends AdminBaseController
{
    public function index()
    {
        return view('admin::management.announcements.index');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function announcementsData(Request $request)
    {
        if ($request->ajax()) {
            $announcements = Announcement::all();
            return Datatables::of($announcements)
                ->editColumn('from_date', function ($r) {
                    return toDate($r->from_date);
                })
                ->editColumn('expired_date', function ($r) {
                    return toDate($r->expired_date);
                })
                ->editColumn('is_active', function ($r) {
                    return $r->is_active ? 'Yes' : 'No';
                })
                ->editColumn('created_date', function ($r) {
                    return toDate($r->created_date);
                })
                ->editColumn('created_by', function ($r) {
                    return ($r->userData) ? $r->userData->firstname . ' ' . $r->userData->lastname : $r->created_by;
                })
                ->editColumn('user_types', function ($r) {
                    $count = $r->userType->count();
                    $types = $r->userType->pluck('id');
                    return '<a href="#" class="user-types" data-types="'.$types.'">'.$count . ' ' . str_plural('Type',$count).'</a>' ;
                })
                ->editColumn('viewed', function ($r) {
                    $count = $r->viewed->count();
                    if(!$count){
                        return '0';
                    }
                    return '<a href="#" class="viewed" data-id="'.$r->id.'">'.number_format($count).'</a>';
                })
                ->addColumn('action', function ($r) {
                    return view('admin::management.announcements.partials._options', ['row' => $r]);
                })
                ->rawColumns(['user_types', 'viewed', 'action'])
                ->make(true);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userTypesData(Request $request)
    {
        if($request->ajax()){
            $userTypes = UserType::whereIn('id',$request->get('ids'))->get();
            return view('admin::management.announcements.partials._user_types',compact('userTypes'));
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewedData(Request $request)
    {
        if($request->ajax()){
            $announcement = Announcement::with('viewed.userData','viewed.userType')
                ->where('id',$request->get('id'))
                ->first();
            return view('admin::management.announcements.partials._viewed',compact('announcement'));
        }
    }

    /**
     * @param AnnouncementRequest $request
     * @param Announcement $announcement
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function createAnnouncement(AnnouncementRequest $request, Announcement $announcement)
    {
        if($request->isMethod('post')) {
            $data = $request->all();
            $create = $announcement->create($data);
            if ($create) {
                $create->userType()->attach($data['user_types']);
                Session::flash('success', 'Announcement Created.');
                return redirect()->route('admin.management.announcements');
            }
        }
        return view('admin::management.announcements.create', compact('announcement'));
    }

    /**
     * @param AnnouncementRequest $request
     * @param Announcement $announcement
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAnnouncement(AnnouncementRequest $request, Announcement $announcement)
    {
        if ($request->isMethod('put')) {
            $data = $request->all();
            $update = $announcement->update($data);
            $announcement->userType()->sync($data['user_types']);
            Session::flash('success', 'Announcement Updated.');
        }
        return redirect()->route('admin.management.announcements');
    }

    /**
     * @param Announcement $announcement
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAnnouncement(Announcement $announcement)
    {
        $announcement->delete();
        $announcement->userType()->detach();
        Session::flash('success', 'Announcement Deleted.');
        return redirect()->route('admin.management.announcements');
    }
}
