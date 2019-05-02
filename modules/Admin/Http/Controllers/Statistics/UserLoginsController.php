<?php

namespace Modules\Admin\Http\Controllers\Statistics;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\Statistics\UserLogsRequest;
use Yajra\DataTables\Datatables;
use Modules\Admin\Repositories\Statistics\UserLoginRepository;

class UserLoginsController extends AdminBaseController
{

    /**
     * Object of UserLoginRepository class
     *
     * @var userLoginRepo
     */
    private $userLoginRepo;
    
    /**
     * Create a new instance of UserLoginsController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->userLoginRepo = new UserLoginRepository();
    }

    /**
     * Get user login index page
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $admins = $this->userLoginRepo->admins();
        return view('admin::statistics.user-logins.index', compact('admins'));
    }

    /**
     * Get JSON data with all rows
     * 
     * @param Request $request
     * 
     * @return mixed
     */
    public function userLoginsData(UserLogsRequest $request)
    {
        if ($request->ajax()) {

            $userLoginDataTable = $this->userLoginRepo->data(
                $request->get('date_from'),
                $request->get('date_to'),
                $request->get('admin')
            );

            return $userLoginDataTable;
        }
    }
}
