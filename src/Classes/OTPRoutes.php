<?php

namespace VS\Auth\Classes;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use VS\Auth\Http\Controllers\OTPController;
use VS\Base\Exceptions\APIException;

class OTPRoutes
{
    public static function make(string $guard, string $controller = OTPController::class,  null|string $prefix = null)
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
//                    Route::post('pin/send/email', [$controller, 'sendViaEmail'])->name('pin.send.email');
//                    Route::post('login', [$controller, 'login'])->name('login');
                });

                // Authenticated Routes
                Route::group(['middleware' => ['auth:' . $guard, 'vs-auth.verified:' . $guard]], function () use ($controller) {
                    Route::post('pin/send/email/{email}', [$controller, 'send'])->name('pin.send');
                });
        });

    }

}
