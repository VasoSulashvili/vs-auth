<?php

namespace VS\Auth;

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

    }

    protected function registerMiddleware()
    {
        // Register the middleware into Laravel's global middleware stack
        $this->app['router']->aliasMiddleware('vs-auth.client.auth', VSAuthClientMiddleware::class);
    }
}
