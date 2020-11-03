<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if('not_customer', function (){
            if(auth()->check())
                return (auth()->user()->role !== 'customer');
            return false;
        });
        Blade::if('not_social_user', function (){
            if(auth()->check())
                return (isset(auth()->user()->password));
            return false;
        });
    }
}
