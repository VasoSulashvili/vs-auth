<?php

declare(strict_types=1);

namespace VSAuth\Repositories;

use Illuminate\Contracts\Auth\Authenticatable;

interface AuthRepositoryInterface
{

    public function register(string $email, string $password) : Authenticatable;


    public function login(string $email, string $password) : ?Authenticatable;


    public function logout();

}
