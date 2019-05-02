<?php

namespace Dashboard\Repositories\Statistics;

use App\Models\Statistics\UserLogin;
use Carbon\Carbon;

class ClientLoginRepository
{

    /**
     * Object of UserLogin class
     *
     * @var $userLogin
     */
    private $userLogin;


    /**
     * ClientLoginRepository constructor.
     */
    public function __construct()
    {
        $this->userLogin = new UserLogin();
    }


    /**
     * @param $id
     */
    public function saveLoginClientDateTime($id)
    {
        $this->userLogin->create([
            'userid' => $id,
            'dts' => Carbon::now()->toDateTimeString(),
            'login' => 'I'
        ]);
    }


    /**
     * @param $id
     */
    public function saveLogOutClientDateTime($id)
    {
        $this->userLogin->create([
            'userid' => $id,
            'dts' => Carbon::now()->toDateTimeString(),
            'login' => 'O'
        ]);
    }
}
