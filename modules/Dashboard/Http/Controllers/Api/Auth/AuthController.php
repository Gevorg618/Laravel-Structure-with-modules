<?php

namespace Dashboard\Http\Controllers\Api\Auth;

use App\Models\Users\User;
use Dashboard\Repositories\Statistics\ClientLoginRepository;
use Illuminate\Support\Facades\Auth;
use Dashboard\Http\Requests\Api\Auth\LoginRequest;
use Dashboard\Http\Controllers\DashboardBaseController;


class AuthController extends DashboardBaseController
{

    /**
     * Object of ClientLoginRepository class
     *
     * @var $clientLoginRepository
     */
    private $clientLoginRepository;


    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->clientLoginRepository = new ClientLoginRepository();
    }


    public function login(LoginRequest $request)
    {

        if (Auth::attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'active' => 'Y'
        ], $request->get('remember'))) {

            $this->clientLoginRepository->saveLoginClientDateTime(Auth::user()->id);
            return response()->json(publicUser());
        }


        return response()->json(['errors' => ['Sorry, we were not able to authenticate the user with the provided credentials.']], 422);
    }

    public function logout()
    {
        $this->clientLoginRepository->saveLogOutClientDateTime(Auth::user()->id);
        Auth::logout();
        return response()->json('ok');
    }
}
