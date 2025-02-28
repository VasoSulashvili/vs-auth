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


    /**
     * @param array $data
     * @return Authenticatable
     */
    public function register(array $data): Authenticatable
    {
        $user = $this->repository->register($data);

        return $user;
    }


    /**
     * @param $guard
     * @param array $data
     * @return Authenticatable
     * @throws APIException
     */
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


    /**
     * @param Authenticatable $user
     * @return bool
     */
    public function logout(Authenticatable $user): bool
    {
        return (bool) $user->tokens()->delete();
    }


    /**
     * @param Authenticatable $user
     * @param array $scopes
     * @return string|null
     */
    public function createPersonalAccessToken(Authenticatable $user, array $scopes = []): null|string
    {
        $user->tokens()->delete();

        return $user->createToken(config('vs-auth.personal_access_client_name'), $scopes)->accessToken;
    }


    /**
     * @param Authenticatable $user
     * @param string $oldPassword
     * @param string $password
     * @param string $passwordConfirmation
     * @return Authenticatable
     * @throws APIException
     */
    public function updatePassword(Authenticatable $user, string $oldPassword, string $password, string $passwordConfirmation): Authenticatable
    {
        if(!Hash::check($oldPassword, $user->password)) {

            throw new APIException('Old password is wrong', 403);

        }
        if($password !== $passwordConfirmation) {

            throw new APIException('New password and repeat password are not same', 403);

        }

        $user->update(['password' => Hash::make($password)]);

        return $user;
    }

}
