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
        $this->registerRoutes();
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
//        $this->app->bind('Feikwok\RedisUI\Http\Controllers\UIController', function() {
//            return UIController::class;
//        });
//        $this->app->make('Feikwok\RedisUI\Http\Controller\UIController');
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
//        $this->publishes([
//            PACKAGE_PATH.'/public' => public_path('vendor/feikwok/redis-ui'),
//        ], 'redis-ui-assets');
    }

    /**
     * Register the Horizon routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'prefix' => 'redis-ui',
//            'namespace' => 'Feikwok\RedisUI\Http\Controller',
            'middleware' => 'web',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }
}
