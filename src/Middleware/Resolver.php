<?php

declare(strict_types=1);

namespace ServiceRunner\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Resolver implements MiddlewareResolver
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $entry
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @return Middleware
     */
    public function __invoke($entry): Middleware
    {
        if (is_object($entry)) {
            return $entry;
        }

        return $this->container->get($entry);
    }
}
