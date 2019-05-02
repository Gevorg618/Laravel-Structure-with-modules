<?php

namespace App\Http\Controllers\Protect;

use Illuminate\Http\Request;
use Illuminate\Cookie\CookieJar;
use App\Http\Controllers\Controller;

class ProtectController extends Controller
{
    public function protect(CookieJar $cookieJar, Request $request)
    {
        if ($request->cookie('protect') == config('app.password')){
            return redirect()->route('admin.login');
        }

        if($request['password']){
           $password = $request['password'];
           if ($password == config('app.password')){
               $cookieJar->queue(cookie('protect',$password, 1440));
               return redirect()->route('admin.login');
           }else{
               return view('admin::auth.protect');
           }
        }
        
        return view('admin::auth.protect');
    }
}
