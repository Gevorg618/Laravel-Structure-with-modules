<?php


namespace App\Listeners;


use Modules\Admin\Repositories\Statistics\UserLoginRepository;
class UserListenerLogout
{
    private $userLoginReo;

    /**
     * UserListenerLogout constructor.
     * @param UserLoginRepository $loginRepository
     */
    public function __construct(UserLoginRepository $loginRepository)
    {
        $this->userLoginReo = $loginRepository;

    }

    /**
     * @param $logout
     */
    public function handle()
    {
        $id = admin()->id;
        $this->userLoginReo->saveLogOutUserDateTime($id);
    }
}
