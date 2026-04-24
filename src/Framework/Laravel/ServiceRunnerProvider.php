<?php

declare(strict_types=1);

namespace ServiceRunner\Framework\Laravel;

use Illuminate\Support\ServiceProvider;
use ServiceRunner\Middleware\MiddlewareResolver;
use ServiceRunner\Middleware\Resolver;
use ServiceRunner\Middleware\Runner;
use ServiceRunner\ServiceConfig;

class ServiceRunnerProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Resolver::class, fn ($app) => new Resolver($app));
        $this->app->alias(Resolver::class, MiddlewareResolver::class);

        $this->app->afterResolving(ServiceConfig::class, function (ServiceConfig $config) {
            $this->registerServices($config);
        });
    }

    private function registerServices(ServiceConfig $config): void
    {
        foreach ($config->services() as $service => $definition) {
            $middlewares = $definition['middlewares'] ?? [];
            $options = $definition['options'] ?? [];

            $this->app->bind($service, function ($app) use ($service, $middlewares, $options) {
                $resolver = $app->make(MiddlewareResolver::class);
                $runner = new Runner($middlewares, $resolver);

                return new $service($options, $runner);
            });
        }
    }
}
