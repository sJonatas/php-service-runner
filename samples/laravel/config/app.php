<?php

return [
    'name' => env('APP_NAME', 'Service Runner Laravel Sample'),
    'env' => env('APP_ENV', 'local'),
    'debug' => (bool) env('APP_DEBUG', true),
    'url' => env('APP_URL', 'http://localhost:8081'),
    'timezone' => 'UTC',
    'key' => env('APP_KEY'),
    'maintenance' => [
        'driver' => 'file',
    ],
];
