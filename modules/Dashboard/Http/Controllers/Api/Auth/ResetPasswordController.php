<?php

namespace Dashboard\Http\Controllers\Api\Auth;

use Mail;
use Hash;
use App\Models\Users\User;
use Illuminate\Support\Facades\Auth;
use Dashboard\Mail\Auth\PasswordReset;
use Illuminate\Support\Facades\Password;
use Dashboard\Http\Controllers\DashboardBaseController;
use Dashboard\Http\Requests\Api\Auth\Password\ResetRequest;
use Dashboard\Http\Requests\Api\Auth\Password\ConfirmRequest;
use Dashboard\Http\Requests\Api\Auth\Password\CompleteRequest;
use Illuminate\Auth\Events\PasswordReset as ResetPasswordEvent;

class ResetPasswordController extends DashboardBaseController
{
    public function reset(ResetRequest $request)
    {
        $user = User::byEmail($request->get('email'))->first();

        Mail::to($user->email)->queue(new PasswordReset($user));
        
        return response()->json('OK');
    }

    public function confirm(ConfirmRequest $request)
    {
        return response()->json('OK');
    }
    
    public function resetComplete(CompleteRequest $request)
    {
        $user = User::byEmail($request->get('email'))->first();

        $user->password = Hash::make($request->get('password'));

        $user->setRememberToken(str_random(60));

        $user->save();

        event(new ResetPasswordEvent($user));

        Password::getRepository()->delete($user);
        
        return response()->json('OK');
    }
}
