<?php

namespace Admin\Repositories;


use App\Models\UserGroupLog;

class UserGroupLogRepository
{
    /**
     * Object of UserGroupLog class
     */
    private $userGroupLog;


    /**
     * UserGroupLogRepository constructor.
     */
    public function __construct()
    {
        $this->userGroupLog = new UserGroupLog();
    }


    /**
     * @param $id
     * @return bool
     */
    public function single($id)
    {
        $userLog = $this->userGroupLog->where('id', $id)->first();

        if (!$userLog) {
            return false;
        }

        return $userLog;
    }


    /**
     * @param $params
     * @return mixed
     */
    public function store($params)
    {
        return $this->userGroupLog->create($params);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function getUserLogs($id)
    {
        $userLogs = $this->userGroupLog->where('id', $id)->get();
        return $userLogs;
    }



}
