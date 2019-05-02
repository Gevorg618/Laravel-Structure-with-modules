<?php


namespace Admin\Repositories\Users;


use App\Models\Users\UserData;

class UserDataRepository
{
    /**
     * Object of UserData class
     *
     * @var user
     */
    private $userData;


    /**
     * UserDataRepository constructor.
     */
    public function __construct()
    {
        $this->userData = new UserData();
    }


    /**
     * @param $params
     * @return mixed
     */
    public function store($params)
    {
        return $this->userData->create($params);
    }

}
