<?php

return [

    'guards' => [
        'api' => [
            'driver' => 'session',
            'provider' => 'people',
        ]
    ],

    'providers' => [
        'people' => [
            'driver' => 'eloquent',
            'model' => env('PERSON_AUTH_MODEL', VSAuth\Models\Person::class),
        ],
    ],

    'passwords' => [
        'people' => [
            'provider' => 'people',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
];
