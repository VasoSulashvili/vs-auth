<?php

declare(strict_types=1);

namespace VSAuth\Repositories;

use VSAuth\Models\Person;

class AuthRepository implements AuthRepositoryInterface
{
    public function __construct(protected Person $model)
    {

    }

}
