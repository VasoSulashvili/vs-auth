<?php

namespace VSAuth\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use VSAuth\Http\Requests\RegisterPersonRequest;

class AuthService
{

    public function __construct(protected Authenticatable $provider)
    {

    }

    public function register(string $email, string $password) : Model
    {

        return $this->provider->create([
            'email' => $email,
            'password' => $password,
        ]);

    }

}
