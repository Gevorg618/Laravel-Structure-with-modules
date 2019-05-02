<?php

namespace App\Providers;


use Horizon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
          base_path() . '/resources/assets' => public_path('vendor'),
        ], 'public');

        Horizon::auth(function ($request) {
            return admin() && in_array(admin()->id, config('admin.super-admins'));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
