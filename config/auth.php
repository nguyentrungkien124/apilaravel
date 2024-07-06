<?php

return [

    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'cus' => [
            'driver' => 'session',
            'provider' => 'khachhang',
        ],
        'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'khachhang' => [
            'driver' => 'eloquent',
            'model' => App\Models\Khachhang::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens', // Change this to your users password reset table
            'expire' => 60,
            'throttle' => 60,
        ],
        'khachhang' => [
            'provider' => 'khachhang',
            'table' => 'khachhang_password_reset_tokens', // Change this to your khachhang password reset table
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];



