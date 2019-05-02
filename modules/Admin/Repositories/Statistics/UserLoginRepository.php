<?php

namespace Modules\Admin\Repositories\Statistics;

use App\Models\Statistics\UserLogin;
use Carbon\Carbon;
use Yajra\DataTables\Datatables;
use DB;

class UserLoginRepository
{

    /**
     * Object of UserLogin class
     *
     * @var $userLogin
     */
    private $userLogin;

    /**
     * UserLoginRepository constructor.
     */
    public function __construct()
    {
        $this->userLogin = new UserLogin();
    }


    /**
     * get admins
     *
     * @return mixed
     */
    public function admins()
    {
        $admins = admins();
        $admins = $admins->keyBy('id')->sortBy('userData.firstname')->map(function ($item) {
            return $item->userData->firstname . ' ' . $item->userData->lastname;
        });
        return $admins;
    }

    /**
     * get users login data
     *
     * @return array
     */
    public function data($dateFrom, $dateTo, $adminId)
    {

        $userLogins = UserLogin::whereBetween('dts', [$dateFrom, $dateTo]);

        if (!empty($adminId)) {
            $userLogins = $userLogins->whereHas('user', function ($query) use ($adminId) {
                $query->where('id', $adminId);
            });
        }

        $userLoginDataTable = Datatables::of($userLogins)
            ->editColumn('userid', function ($userLogin) {

                return $userLogin->user && $userLogin->user->userData ? $userLogin->user->userData->firstname . ' ' . $userLogin->user->userData->lastname : 'N/A';
            })
            ->editColumn('login', function ($userLogin) {
                return $userLogin->types[$userLogin->login];
            })
            ->make(true);

        return $userLoginDataTable;
    }


    /**
     * @param $id
     */
    public function saveLoginUserDateTime($id)
    {
        $currentTime = Carbon::now()->toDateTimeString();
        $data = [
                'userid' => $id,
                'dts' => $currentTime,
                'login' => 'I'
            ];

         $this->userLogin->create($data);
    }


    /**
     * @param $id
     */
    public function saveLogOutUserDateTime($id)
    {
        $currentTime = Carbon::now()->toDateTimeString();
        $data = [
                'userid' => $id,
                'dts' => $currentTime,
                'login' => 'O'
            ];

        $this->userLogin->create($data);
    }
}
