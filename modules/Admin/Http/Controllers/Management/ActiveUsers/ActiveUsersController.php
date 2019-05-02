<?php

namespace Modules\Admin\Http\Controllers\Management\ActiveUsers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Session;
use Carbon\Carbon;
use App\Models\Management\UserType;
use Modules\Admin\Http\Controllers\AdminBaseController;

class ActiveUsersController extends AdminBaseController {

    /**
    * Index
    * @return view
    */
    public function index()
    {
        $time = 15;
        $timestamp = Carbon::now()->subMinute($time)->timestamp;
        $totalActiveSessions = Session::getTotalActiveSessions($timestamp);
        $totalActiveGuestSessions = Session::getTotalActiveGuestSessions($timestamp);
        $userTypes = UserType::all();
        $webView = $this->webView($time, $userTypes);
        $appView = $this->appView($time, $userTypes);

        return view('admin::management.active_users.index',
            compact(
                    'time',
                    'totalActiveSessions',
                    'totalActiveGuestSessions',
                    'webView',
                    'appView'
                )
        );
    }

    /**
    * Web Tab
    * @return view
    */
    private function webView($time, $userTypes)
    {
        return view('admin::management.active_users.partials._web',
                compact(
                        'userTypes',
                        'time'
                    )
            );
    }

    /**
    * App Tab
    * @return view
    */
    private function appView($time, $userTypes)
    {
        return view('admin::management.active_users.partials._app',
                compact(
                        'userTypes',
                        'time'
                    )
            );
    }

}
