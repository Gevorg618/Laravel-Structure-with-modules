<?php

namespace Modules\Admin\Http\Controllers\Integrations\APIUsers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Carbon\Carbon, Session, Input, Html, DB, Validator, Auth, Response, Exception;
use App\Models\Integrations\APIUsers\{ APIUser, APILog, APIEmailSetting, APIEmailSettingContent, APIUserGroup };
use Modules\Admin\Http\Requests\Integrations\APIUsers\{ SearchLogRequest, APIUserRequest };
use App\Models\Clients\Client;
use App\Models\Api\Subscriber;

class APIUsersController extends AdminBaseController {

    /**
    * Index
    * @return view
    */
    public function index()
    {
        return view('admin::integrations.api_users.index');
    }

    /**
    * create
    * @return view
    */
    public function create()
    {
        $informationView = $this->informationView();
        $settingsView = $this->settingsView();
        $permissionsView = $this->permissionsView();
        $emailsView = $this->emailsView();
        $subscribersView = $this->subscribersView();

        return view('admin::integrations.api_users.create_edit',
                compact(
                        'informationView',
                        'settingsView',
                        'permissionsView',
                        'emailsView',
                        'subscribersView'
                    )
            );
    }

    /**
    * store
    * @return view
    */
    public function store(APIUserRequest $request)
    {
        $inputs = $request->sortInputs();
        $saveApiUser = APIUser::saveApiUser(null, $inputs);
        $saveApiUser ? Session::flash('error', $saveApiUser) : Session::flash('success', 'API User Successfully Created.');
        return redirect(route('admin.integrations.api-users'));
    }

    /**
    * edit
    * @return view
    */
    public function edit($id)
    {
        $apiUser = APIUser::getById($id);

        $informationView = $this->informationView($id, $apiUser);
        $settingsView = $this->settingsView($id, $apiUser);
        $permissionsView = $this->permissionsView($id, $apiUser);
        $emailsView = $this->emailsView($id, $apiUser);
        $subscribersView = $this->subscribersView($id, $apiUser);

        return view('admin::integrations.api_users.create_edit',
                compact(
                        'informationView',
                        'settingsView',
                        'permissionsView',
                        'emailsView',
                        'subscribersView',
                        'apiUser'
                    )
            );
    }

    /**
    * update
    * @return view
    */
    public function update($id, APIUserRequest $request)
    {
        $inputs = $request->sortInputs();
        $saveApiUser = APIUser::saveApiUser($id, $inputs);
        $saveApiUser ? Session::flash('error', $saveApiUser) : Session::flash('success', 'API User Successfully Updated.');
        return redirect()->back();
    }

    /**
    * update
    * @return view
    */
    public function destroy($id)
    {
        $saveApiUser = APIUser::where('id', $id)->first();

        if($saveApiUser->is_active) {
            Session::flash('success', 'Sorry, That record is in active mode. Please disable it first.');
            return redirect()->back();
        }
        APIUser::where('id', $id)->delete();
        APIEmailSettingContent::where('api_user_id', $id)->delete();

        Session::flash('success', 'API User Successfully Deleted.');
        return redirect()->back();
    }

    /**
    * Logs
    * @param $id
    * @return view
    */
    public function logs($id)
    {
        $apiUser = APIUser::getById($id);
        $logs = APILog::getAll($id);
        $count = APILog::getCount($id);

        return view('admin::integrations.api_users.logs',
            compact(
                    'apiUser',
                    'logs',
                    'count'
                )
            );
    }

    /**
    * Logs
    * @param $id
    * @return view
    */
    public function search(SearchLogRequest $request)
    {
        $inputs = $request->all();
        $apiUser = APIUser::getById($inputs['id']);
        $logs = APILog::search($inputs);
        $count = $logs->total();

        return view('admin::integrations.api_users.logs',
            compact(
                    'apiUser',
                    'logs',
                    'count'
                )
            );
    }

    /**
    * Logs content
    * @param $id
    * @return string
    */
    public function logsContent($id)
    {
        $content = APILog::getContent($id);
        $htmlspecialchars = htmlspecialchars(print_r(json_decode($content->log), true),ENT_QUOTES,'UTF-8');
        $html = json_encode(['html' => $htmlspecialchars]);
        return $html;
    }

    /**
    * informatinView
    * @param $id, $apiUser
    * @return view
    */
    private function informationView($id = null, $apiUser = null)
    {
        $clients = Client::getAllClients();
        $savedClients = !is_null($id) ? APIUserGroup::getSavedApiUsers($id) : [];

        return view('admin::integrations.api_users.partials._information',
            compact(
                    'apiUser',
                    'clients',
                    'savedClients'
                )
            );
    }

    /**
    * settingsView
    * @param $id, $apiUser
    * @return view
    */
    private function settingsView($id = null, $apiUser = null)
    {
        return view('admin::integrations.api_users.partials._settings',
            compact(
                    'apiUser'
                )
            );
    }

    /**
    * permissionsView
    * @param $id, $apiUser
    * @return view
    */
    private function permissionsView($id = null, $apiUser = null)
    {
        $permissionList = getPermissionList();
        $savedPermissions = !is_null($apiUser) ? unserialize($apiUser->permissions) : [];

        return view('admin::integrations.api_users.partials._permissions',
            compact(
                    'apiUser',
                    'permissionList',
                    'savedPermissions'
                )
            );
    }

    /**
    * emailsView
    * @param $id, $apiUser
    * @return view
    */
    private function emailsView($id = null, $apiUser = null)
    {
        $settings = APIEmailSetting::getAPIEmailSettings();

        return view('admin::integrations.api_users.partials._emails',
            compact(
                    'apiUser',
                    'settings'
                )
            );
    }

    /**
    * subscribersView
    * @param $id, $apiUser
    * @return view
    */
    private function subscribersView($id = null, $apiUser = null)
    {
        $subscribers = Subscriber::getAPISubscribers($id);

        return view('admin::integrations.api_users.partials._subscribers',
            compact(
                    'apiUser',
                    'subscribers'
                )
            );
    }

    /**
     * Gather data for index page and datatables
     */
    public function data()
    {
        $apiUsers = APIUser::getAll();

        return Datatables::of($apiUsers)
            ->editColumn('in_production', function ($r) {
                return $r->in_production ? 'Live' : 'Test';
            })
            ->editColumn('is_active', function ($r) {
                return $r->is_active ? 'Active' : 'Not Active';
            })
            ->editColumn('is_visible_all', function ($r) {
                return $r->is_visible_all ? 'Yes' : 'No';
            })
            ->editColumn('created', function ($r) {
                return Carbon::createFromTimeStamp($r->created)->format('m/d/Y');
            })
            ->editColumn('last_call', function ($r) {
                return $r->last_call ? Carbon::createFromTimeStamp($r->last_call)->format('m/d/Y H:i:s') : 'N/A';
            })
            ->editColumn('passed_calls', function ($r) {
                return number_format($r->passed_calls);
            })
            ->editColumn('failed_calls', function ($r) {
                return number_format($r->failed_calls);
            })
            ->addColumn('action', function ($r) {
                return view('admin::integrations.api_users.partials._options', ['row' => $r]);
            })
            ->make(true);
    }
}
