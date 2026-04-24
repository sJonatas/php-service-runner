<?php

declare(strict_types=1);

namespace App\Providers;

use App\Config\AppServiceConfig;
use Illuminate\Support\ServiceProvider;
use ServiceRunner\ServiceConfig;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ServiceConfig::class, AppServiceConfig::class);
    }

    public function boot(): void
    {
        $this->app->make(ServiceConfig::class);
    }
}
