<?php

namespace Modules\Admin\Repositories;


use App\Models\Integrations\APIUsers\APIUser;
use Illuminate\Support\Collection;

class ApiUserRepository
{

    /**
     * Object of APIUser class
     *
     * @var $apiUser
     */
    private $apiUser;


    /**
     * ApiUserRepository constructor.
     */
    public function __construct()
    {
        $this->apiUser = new APIUser();
    }


    /**
     * @return Collection
     */
    public function getAPIAccounts()
    {
        return APIUser::orderBy('title')->pluck('title', 'id');
    }


    /**
     * @return mixed
     */
    public function getAPIUser()
    {
        return $this->apiUser->orderBy('title')->pluck('title', 'id');
    }
}
