<?php

return [
    'defaults' => [
        'guard' => 'admin',
        'passwords' => 'users',
    ],

    'guards' => [
        'admin' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => Admin\Models\User::class,
        ],
    ],
];
