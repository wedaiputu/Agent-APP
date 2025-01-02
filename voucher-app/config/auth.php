<?php

return [

    'default' => env('AUTH_DEFAULT_GUARD', 'web'),

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'agent' => [
            'driver' => 'session',
            'provider' => 'agents',
        ],

        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
            'hash' => false,
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'agents' => [
            'driver' => 'eloquent',
            'model' => App\Models\Agent::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
        ],

        'agents' => [
            'provider' => 'agents',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],

    'redirects' => [
        'password' => '/password/reset',
    ],

    'password_timeout' => 10800,

];
