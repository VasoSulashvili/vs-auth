<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use VSAuth\Http\Controllers\AuthController;

Route::group(['prefix' => 'api/vs-auth'], function () {
//    Route::get('test', function() {
//        return 'test';
//    });


    Route::post('register', [AuthController::class, 'register'])->name('register');

});
