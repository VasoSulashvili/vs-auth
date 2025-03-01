<?php

namespace VS\Auth\Classes;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use VS\Base\Exceptions\APIException;

class EmailVerificationRoutes
{
    public static function make(string $controller, string $guard, null|string $prefix = null)
    {
        if(!class_exists($controller)) {
            throw new APIException('Controller not found.');
        }
        // Email verification routes
        return Route::group(['middleware' => ['api', 'force.json'], 'as' => $prefix ? $prefix . '.' : ''], function () use ($controller, $guard) {
            Route::get('/email/verify/{id}/{hash}', [$controller, 'verify'])->name('verification.verify');
            Route::post('/email/verify/resend', [$controller, 'resend'])->middleware('auth:' . $guard)->name('verification.resend');
        });

    }

}
