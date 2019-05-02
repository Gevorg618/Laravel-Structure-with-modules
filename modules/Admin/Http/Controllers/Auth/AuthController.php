<?php

namespace Modules\Admin\Http\Controllers\Auth;

use App\Events\UserEventLogout;
use App\Models\Users\User;
use Illuminate\Auth\Events\Logout;
use Validator, Session, Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Modules\Admin\Repositories\Statistics\UserLoginRepository;



class AuthController extends AdminBaseController
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    protected $guard = 'admin';

    /**
     * Object of UserLoginRepository class
     *
     * @var userLoginRepository
     */
    private $userLoginRepository;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin.guest', ['except' => 'logout']);
        $this->userLoginRepository = new UserLoginRepository();
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('admin::auth.login');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard($this->guard);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'active' => 'Y'
        ];
    }


    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->id) {
            $this->userLoginRepository->saveLoginUserDateTime($user->id);
        }

    }


    /**
     * Log the user out of the application.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->regenerate();
        Auth::logout();
        return redirect($this->redirectTo);
    }
}
