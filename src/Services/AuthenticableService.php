<?php

namespace VS\Auth\Services;

class AuthenticableService
{
    public function __construct(protected string $guard)
    {
    }

    /**
     * @param string $email
     * @return mixed
     * @throws \VS\Base\Exceptions\APIException
     */
    public function findByEmail(string $email): mixed
    {
        $model = GuardService::model($this->guard);

        return $model::where('email', $email)->first();
    }

}
