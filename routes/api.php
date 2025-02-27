<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/vs-auth', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
