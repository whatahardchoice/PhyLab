<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
        defind the constant of the app
        */
        if(!defined('SUCCESS_MESSAGE')) define('SUCCESS_MESSAGE', "success");
        if(!defined('FAIL_MESSAGE')) define('FAIL_MESSAGE',"fail");
        Validator::extend('studentId', function($attribute, $value, $parameters)
        {
            return preg_match('/^\d{8}$/', $value);
        });
        require app_path().'/Http/helper.php';
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        //
//        if ($this->app->environment() !== 'production') {
//            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
//        }
    }
}
