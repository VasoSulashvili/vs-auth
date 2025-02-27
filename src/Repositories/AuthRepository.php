<?php

namespace VS\Auth\Repositories;

use Illuminate\Contracts\Auth\Authenticatable;

class AuthRepository
{
    protected $model;

    public function __construct(Authenticatable $model)
    {
        $this->model = $model;
    }

    public function register(array $data)
    {
        return $this->model->create($data);
    }
}
