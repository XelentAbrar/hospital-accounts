<?php

namespace XelentAbrar\HospitalAccounts;

use Illuminate\Support\ServiceProvider;

class HospitalAccountsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'HospitalAccounts');
        $this->loadViewsFrom(__DIR__.'/../resources/js', 'jspages');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([
            __DIR__.'/../config/accounts.php' => config_path('accounts.php'),
        ], 'config');


        $this->publishes([
            __DIR__.'/../resources/js/Pages' => resource_path('js/Pages'),
        ], 'accounts-vue');

        // $this->publishes([
        //     __DIR__.'/routes/admin' => resource_path('./../routes/admin'),
        // ], 'accounts-routes');

    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/accounts.php', 'accounts');
    }
}
