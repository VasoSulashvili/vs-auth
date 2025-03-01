<?php

namespace VS\Auth\Repositories;

use Illuminate\Contracts\Auth\Authenticatable;

class PasswordRepository
{
    protected $model;

    public function __construct(Authenticatable $model)
    {
        $this->model = $model;
    }

    public function update(Authenticatable $user, string $password)
    {
        return $user->update(['password' => bcrypt($password)]);
    }
}
