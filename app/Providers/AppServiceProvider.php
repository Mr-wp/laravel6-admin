<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
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
        //error_reporting(E_ERROR | E_WARNING | E_PARSE);
//        DB::listen(function ($query) {
//             dump($query->sql);
//             $query->bindings;
//             $query->time;
//        });
    }


}
