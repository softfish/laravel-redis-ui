<?php

namespace Feikwok\RedisUI;

use Feikwok\RedisUI\Http\Controllers\UIController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LaravelRedisUIServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! defined('PACKAGE_PATH')) {
            define('PACKAGE_PATH', realpath(__DIR__.'/../'));
        }

        $this->registerResources();
        // $this->registerRoutes();
        $this->defineAssetPublishing();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
    }

    /**
     * Register the package Resources
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'redis-ui');
    }

    /**
     * Define the asset publishing configuration.
     *
     * @return void
     */
    public function defineAssetPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                PACKAGE_PATH.'/resources/public' => public_path('vendor/feikwok/laravel-redis-ui'),
            ], 'laravel-redis-ui');
        }

    }

    // /**
    //  * Register package routes.
    //  *
    //  * @return void
    //  */
    // protected function registerRoutes()
    // {
    //     Route::group([
    //         'prefix' => 'redis-ui',
    //         'namespace' => 'Feikwok\RedisUI\Http\Controllers',
    //         'middleware' => 'web',
    //     ], function () {
    //         $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    //     });
    // }
}
