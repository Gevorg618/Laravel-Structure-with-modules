<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class Password
{
    protected $except = 'protect';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->path() != $this->except){
            if (env('PASSWORD')) {
                if ($password = $request->cookie('protect')) {
                    if ($password == config('app.password')) {
                        return $next($request);
                    } else {
                        return redirect()->route('protect');
                    }
                } else {
                    return redirect()->route('protect');
                }
            }
        }
        return $next($request);
    }
}
