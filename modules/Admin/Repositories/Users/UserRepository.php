<?php

namespace Modules\Admin\Repositories\Users;

use App\Models\Appraisal\AppraiserPayment;
use App\Models\Appraisal\Order;
use App\Models\Appraisal\OrderAddFee;
use App\Models\Users\User;
use App\Models\Accounting\VendorTaxChange;
use DB;
use Modules\Admin\Helpers\StringHelper;

class UserRepository
{
    /**
     * Object of User class
     *
     * @var user
     */
    private $user;

    /**
     * UserRepository constructor.
     *
     * @param User $user
     */
    public function __construct()
    {
        $this->user = new User();
    }


    /**
     * @param $params
     * @return mixed
     */
    public function store($params)
    {
        return $this->user->create($params);
    }


    /**
     * search on user_data and users table by (Full name and email)
     *
     * @param string $request
     *
     * @return array
     */
    public function searchManagers($request)
    {
        $users = $this->user;
        $users = $users->where('user_type', User::APPRAISER)->where(function ($query) use ($request) {
                $query->whereHas('userData', function ($q) use ($request) {
                    $q->where(\DB::raw('CONCAT_WS(" ", user_data.firstname, user_data.lastname)'), 'like', '%' . $request . '%');
                })->orwhere('email', 'LIKE', '%' . $request . '%');
        });
        $users = $users->orderBy('id', 'desc')->take(15)->with('userData')->get();
        $dataArray = [
            'users' => $users,
        ];

        return $dataArray;
    }

    /**
     * @param $request
     *
     * @return array
     */
    public function searchAppraisers($request)
    {
        $users = $this->user;
        $users = $users->where('user_type', User::USER_TYPE_APPRAISER)
            ->doesntHave('appraiserGroups')
            ->where(function ($query) use ($request) {
            $query->whereHas('userData', function ($q) use ($request) {
                $q->where(\DB::raw('CONCAT_WS(" ", user_data.firstname, user_data.lastname)'), 'like', '%' . $request . '%');
            })->orwhere('email', 'LIKE', '%' . $request . '%');
        });
        $users = $users->orderBy('id', 'desc')->take(15)->with('userData')->get();
        $dataArray = [
            'users' => $users,
        ];

        return $dataArray;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAssignUsers()
    {
        return $this->user
            ->leftJoin('user_data', 'user_data.user_id', '=', 'user.id')
            ->where([
                ['user_type', '=', 1],
                ['active', '=', 'Y'],
                ['show_in_assign', '=', 1]
            ])->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')
            ->get();
    }

    /**
     * @param array $emails
     * @return array
     */
    public function getUsersByEmail($emails)
    {
        $users = [];

        foreach ($emails as $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $users[] = $user;
            }
        }

        return $users;
    }

    /**
     * @param $email
     * @return mixed
     */
    public function getUserInfoByEmailAddress($email)
    {
        return User::where('email', $email)->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUserInfoById($id)
    {
        return User::where('id', $id)->first();
    }

    public function getSupportTicketsUsersForStats()
    {
        return User::with('userData')
            ->where('user_type', 1)
            ->where('active', 'Y')
            ->whereIn('admin_priv', ['R', 'S', 'T'])
            ->get();
    }

    /**
     * @param $year
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getVendorTaxInfoUsers($year)
    {
        return AppraiserPayment::from('appraiser_payments as p')
            ->select(\DB::raw("
                u.id,
                u.email,
                o.firstname,
                o.lastname,
                o.phone,
                o.mobile,
                o.ein,
                o.tax_class,
                o.payable_company,
                TRIM(CONCAT(o.payable_address,' ',o.payable_address1)) as address,
                o.payable_city,
                o.payable_state,
                o.payable_zip
            "))->leftJoin(
                'user as u',
                'u.id',
                '=',
                'p.apprid'
            )->leftJoin(
                'user_data as o',
                'u.id',
                '=',
                'o.user_id'
            )->where('p.paid', '>=', $year . '-01-01')
            ->groupBy('p.apprid')
            ->orderBy('o.ein')->get();
    }

    /**
     *
     * @param
     * @return collection
     */
    public function userSerchByEmailOrId($email, $id)
    {
        return User::where('user_type', User::USER_TYPE_CLIENT)->orWhere('email', 'LIKE', '%'. $email .'%')->
        orWhere('id', 'LIKE', '%'. $id .'%')->limit(15)->select('email')->get();
    }

    /**
     * isAdminUser
     * @return bool
     */
    public function isAdminUser()
    {
        $user = $this->user->where('id', getUserId())->first();
        return (in_array($user->admin_priv, array('S', 'T', 'R', 'O')) && $user->user_type == 1) ? true : false;
    }

    /**
     *
     * @return collection
     */
    public function usersForDashboardStats($fromDate, $toDate, $type)
    {
        return $this->user->where('user_type', $type)->where('active', 'Y')->withCount(['dashboardDelayOrder' => function ($dashboardDelayOrderQuery) use ($fromDate, $toDate) {

            $dashboardDelayOrderQuery->where('created_date', '>=', $fromDate)->where('created_date', '<=', $toDate);

        }])->with(['apprUserView' => function ($apprUserViewQuery) use ($fromDate, $toDate) {

            $apprUserViewQuery->where('created_date', '>=', $fromDate)->where('created_date', '<=', $toDate);

        }])->withCount(['apprDashboardToTransfers' => function ($apprDashboardToTransfersQuery) use ($fromDate, $toDate) {

            $apprDashboardToTransfersQuery->where('is_pause' , 0)->where('created_date', '>=', $fromDate)->where('created_date', '<=', $toDate);

        }]);

    }

    public function getUsers($data = [])
    {
        $users = $this->getUsersQuery($data);
        return $users->paginate(50);
    }

    protected function getUsersQuery($data = [])
    {
        $users = User::with(['userData', 'userGroups']);
        if (isset($data['user_type']) && !empty($data['user_type'])) {
            $users = $users->where('user_type', $data['user_type']);
        }
        if (!isset($data['state'], $data['is_priority'], $data['search'])) {
            return $users;
        }
        $search = '';
        if (isset($data['search']) && !empty($data['search'])) {
            $search = '%' . $data['search'] . '%';
            $users = $users->where('email', 'like', $search);
        }
        return $users->whereHas('userData', function ($query) use ($data, $search) {
            if (isset($data['state']) && !empty($data['state'])) {
                $query = $query->where('state', $data['state']);
            }
            if (isset($data['is_priority']) && !empty($data['is_priority'])) {
                $query = $query->where('is_priority_appr', $data['is_priority']);
            }
            if (isset($data['search']) && !empty($data['search'])) {
                $query = $query->where(function ($q) use ($search) {
                    return $q->where('firstname', 'like', $search)
                        ->orWhere('lastname', 'like', $search);
                });
            }
            return $query;
        });
    }

    /**
     *
     * @return collection
     */
    public function userPrivilege($adminPriv, $userActive)
    {
        return $this->user->where('admin_priv', $adminPriv)->where('active', $userActive);
    }

    /**
     *
     * @return collection
     */
    public function userSales($adminPriv, $userActive, $userId)
    {
        return $this->user->where('id', $userId)->where('admin_priv', $adminPriv)->where('active', $userActive)->with(['groupsBySales']);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUserById($id)
    {
        return $this->user->where('id', $id)->first();
    }


}
