<?php

declare(strict_types=1);

namespace ServiceRunner\Framework\CakePHP;

use Cake\Core\ContainerInterface;
use Cake\Core\ServiceProvider;
use ServiceRunner\Middleware\MiddlewareResolver;
use ServiceRunner\Middleware\Resolver;
use ServiceRunner\Middleware\Runner;
use ServiceRunner\ServiceConfig;

class ServiceRunnerProvider extends ServiceProvider
{
    protected array $provides = [
        Resolver::class,
        MiddlewareResolver::class,
    ];

    public function services(ContainerInterface $container): void
    {
        $container->addShared(Resolver::class, function () use ($container) {
            return new Resolver($container);
        });

        $container->add(MiddlewareResolver::class, function () use ($container) {
            return $container->get(Resolver::class);
        });

        $config = $container->get(ServiceConfig::class);

        foreach ($config->services() as $service => $definition) {
            $middlewares = $definition['middlewares'] ?? [];
            $options = $definition['options'] ?? [];

            $container->add($service, function () use ($container, $service, $middlewares, $options) {
                $resolver = $container->get(MiddlewareResolver::class);
                $runner = new Runner($middlewares, $resolver);

                return new $service($options, $runner);
            });
        }
    }
}
