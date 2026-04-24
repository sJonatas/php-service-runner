<?php

declare(strict_types=1);

namespace ServiceRunner\Middleware;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;

class Resolver implements MiddlewareResolver
{
    /**
     * @var Container
     */
    private $container;

    /**
     * Resolver constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $entry
     * @throws BindingResolutionException
     * @return Middleware
     */
    public function __invoke($entry): Middleware
    {
        if (is_object($entry)) {
            return $entry;
        }

        return $this->container->make($entry);
    }
}
