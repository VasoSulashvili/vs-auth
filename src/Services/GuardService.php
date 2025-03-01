<?php

namespace VS\Auth\Services;

use VS\Base\Exceptions\APIException;

class GuardService
{
    /**
     * @param string $guard
     * @return string
     * @throws APIException
     */
    public static function model(string $guard): string
    {
        $provider = config('auth.guards.' . $guard . '.provider');

        if (!$provider) {
            throw new APIException('Provider not found for guard: ' . $guard);
        } elseif (!class_exists(config('auth.providers.' . $provider . '.model'))) {
            throw new APIException('Model not found for provider: ' . $provider);
        }

        return config('auth.providers.' . $provider . '.model');
    }

}
