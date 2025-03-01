<?php

namespace VS\Auth\Classes;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use VS\Base\Exceptions\APIException;

class PasswordRoutes
{
    public static function make(string $controller, string $guard,  null|string $prefix = null)
    {
        if(!class_exists($controller)) {
            throw new APIException('Controller not found.');
        }
        return Route::group([
            'middleware' => ['api', 'force.json'],
            'as' => $prefix ? $prefix . '.' : ''],
            function () use ($controller, $guard) {

                // Guest Routes
                Route::group(['middleware' => ['vs-auth.client.auth']], function () use ($controller) {
                    Route::post('password/reset/link', [$controller, 'sendResetLinkEmail'])->name('password.reset.link');
                });
                Route::group(['middleware' => ['vs-auth.client.auth']], function () use ($controller) {
                    Route::post('password/reset/{token}', [$controller, 'reset'])->name('password.reset');
                });

                // Authenticated Routes
                Route::group(['middleware' => ['auth:' . $guard, 'vs-auth.verified:' . $guard]], function () use ($controller) {
//                    Route::post('logout', [$controller, 'logout'])->name('logout');
                });
        });

    }

}
