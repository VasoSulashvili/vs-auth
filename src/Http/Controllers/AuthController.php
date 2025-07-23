<?php

declare(strict_types=1);

namespace VSAuth\Http\Controllers;

use App\Http\Controllers\Controller;
use VSAuth\Events\UserRegistered;
use VSAuth\Http\Requests\RegisterPersonRequest;
use VSAuth\Http\Requests\UpdatePersonRequest;
use VSAuth\Models\Person;
use VSAuth\Services\AuthService;

class AuthController extends Controller
{

    public function register(RegisterPersonRequest $request)
    {
        $authService = new AuthService(new Person);

        return $authService->register(
            $request->input('email'),
            $request->input('password')
        );
        return $request->all();
//        event(new UserRegistered($request->user()));

    }

}
