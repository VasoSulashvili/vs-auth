<?php

namespace VS\Auth;

use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;
use Illuminate\Support\ServiceProvider;
use VS\Admin\Http\Middleware\AdminAuth;
use VS\Auth\Enums\TwoFAScope;
use VS\Auth\Http\Middleware\TwoFAVerifyMiddleware;
use VS\Auth\Http\Middleware\VSAuthClientMiddleware;
use Laravel\Passport\Http\Middleware\CheckClientCredentials;
use VS\Auth\Http\Middleware\VSEmailIsVerified;

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
        Passport::tokensCan([
            // 2-Factor Authentication
            TwoFAScope::TwoFAVerified->value => 'Two Factor Authentication Verified',
        ]);

        $this->registerMiddleware();

        Passport::hashClientSecrets();

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
//        Route::prefix('api/admin')
//            ->middleware('api')
//            ->group(__DIR__ . '/../routes/api.php');

    }

    protected function registerMiddleware()
    {

        $this->app['router']->aliasMiddleware('vs-auth.client.auth', VSAuthClientMiddleware::class);
        $this->app['router']->aliasMiddleware('vs-auth.verified', VSEmailIsVerified::class);
        $this->app['router']->aliasMiddleware('vs-auth.two.fa.verify', TwoFAVerifyMiddleware::class);
        $this->app['router']->aliasMiddleware('2fa', \PragmaRX\Google2FALaravel\Middleware::class,);

    }
}
