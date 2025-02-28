<?php

declare(strict_types=1);

namespace VS\Auth\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use VS\Auth\Repositories\AuthRepository;
use VS\Base\Exceptions\APIException;

class AuthService
{
    protected $repository;

//    protected $model;

    public function __construct(Authenticatable $model)
    {
        $this->repository = new AuthRepository($model);
    }


    public function register(array $data): Authenticatable
    {
        $user = $this->repository->register($data);

        return $user;
    }


    public function login($guard, array $data): Authenticatable
    {
        $model = config('auth.providers.' . $guard . '.model');

        if($model) {
            $user = $model::where('email', $data['email'])->first();
            if($user) {
                if(Hash::check($data['password'], $user->password)) {
                    return $user;
                } else {
                    throw new APIException('Wrong password', 404);
                }
            } else {
                throw new APIException('Wrong email address', 404);
            }
        } else {
            throw new APIException('Guard not found', 404);
        }
    }


    public function logout(Authenticatable $user): bool
    {
        return (bool) $user->tokens()->delete();
    }


    public function createPersonalAccessToken(Authenticatable $user, array $scopes = []): null|string
    {
        $user->tokens()->delete();
        return $user->createToken(
            config('vs-auth.personal_access_client_name'),
            $scopes
        )->accessToken;
    }

}
