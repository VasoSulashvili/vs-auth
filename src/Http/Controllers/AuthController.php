<?php

declare(strict_types=1);

namespace VSAuth\Http\Controllers;

use App\Http\Controllers\Controller;
use VSAuth\Http\Requests\RegisterPersonRequest;
use VSAuth\Services\AuthService;

class AuthController extends Controller
{

    public function register(RegisterPersonRequest $request, AuthService $authService)
    {

        return $authService->register(
            $request->input('email'),
            $request->input('password')
        );
        return $request->all();
//        event(new UserRegistered($request->user()));

    }

}
