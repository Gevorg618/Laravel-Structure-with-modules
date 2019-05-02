<?php

namespace Modules\Admin\Http\Controllers\Statistics;

use App\Helpers\Widget;
use App\Models\Appraisal\LogType;
use App\Models\Statistics\UserLog;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\Statistics\UserLogsRequest;
use Yajra\DataTables\Datatables;

class UserLogsController extends AdminBaseController
{

    public function index()
    {
        $admins = admins();
        $admins = $admins->keyBy('id')->sortBy('userData.firstname')->map(function ($item) {
            return $item->userData->firstname . ' ' . $item->userData->lastname;
        });
        $logType = multiselect(LogType::all()->sortBy('title'), 'title');
        return view('admin::statistics.user-logs.index', compact('admins', 'logType'));
    }

    /**
     * @param UserLogsRequest $request
     * @return mixed
     */
    public function userLogsData(UserLogsRequest $request)
    {
        if ($request->ajax()) {
            $userLogs = collect();
            if (!empty($request->filter['date_from']) && !empty($request->filter['date_to']) && !empty($request->filter['admin'])) {
                $userLogs = UserLog::select([
                    'id',
                    'dts',
                    'type_id',
                    'info',
                    'html_content'
                ])->filter($request->filter)->get();
            }
            return Datatables::of($userLogs)
                ->editColumn('type', function ($r) {
                    return $r->type_id > 0 ? $r->logType->title : 'Process';
                })
                ->editColumn('subject', function ($r) {
                    if (!empty($r->html_content)) {
                        $link = '<a href="#" data-id="' . $r->id . '" class="get-html-content">' . $r->info . '</a>';
                        return $link;
                    }
                    return $r->info;
                })
                ->make(true);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getHtmlContent(Request $request)
    {
        if (!empty($request->id)) {
            $userLog = UserLog::select('id', 'dts', 'email')->where('id', $request->id)->first();
            $userLog->dts = Carbon::createFromTimestamp(strtotime($userLog->dts))->toDayDateTimeString();
            return view('admin::statistics.user-logs.partials._modal', compact('userLog'));
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function loadIframe($id)
    {
        $userLog = UserLog::select('html_content')->where('id', $id)->first();
        return $userLog->html_content;
    }
}
