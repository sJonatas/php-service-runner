<?php

declare(strict_types=1);

namespace App\Config;

use App\Middleware\HashPassword;
use App\Middleware\PersistUser;
use App\Middleware\ValidateEmail;
use App\Service\CreateUserService;
use ServiceRunner\ServiceConfig;

class AppServiceConfig implements ServiceConfig
{
    public function services(): array
    {
        return [
            CreateUserService::class => [
                'middlewares' => [
                    PersistUser::class,
                    HashPassword::class,
                    ValidateEmail::class,
                ],
                'options' => [],
            ],
        ];
    }
}
