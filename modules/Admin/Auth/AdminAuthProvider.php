<?php

namespace Modules\Admin\Auth;

use App\Models\Users\User;
use Modules\Admin\Auth\AdminUserProvider;
use Illuminate\Support\ServiceProvider;
use App\Hash\Hasher;

class AdminAuthProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->provider('admin',function()
        {
            return new AdminUserProvider(new Hasher, new User);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}