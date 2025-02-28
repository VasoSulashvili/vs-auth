<?php

namespace VS\Auth;

use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;
use Illuminate\Support\ServiceProvider;
use VS\Admin\Http\Middleware\AdminAuth;
use VS\Auth\Http\Middleware\VSAuthClientMiddleware;
use Laravel\Passport\Http\Middleware\CheckClientCredentials;
class VSAuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerMiddleware();

        Passport::hashClientSecrets();

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
//        Route::prefix('api/admin')
//            ->middleware('api')
//            ->group(__DIR__ . '/../routes/api.php');

    }

    protected function registerMiddleware()
    {

        $this->app['router']->aliasMiddleware('vs-auth.client.auth', VSAuthClientMiddleware::class);

    }
}
