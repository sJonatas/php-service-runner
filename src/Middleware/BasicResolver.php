<?php

declare(strict_types=1);

namespace ServiceRunner\Middleware;

class BasicResolver implements MiddlewareResolver
{
    /**
     * Resolves a middleware entry by instantiating the class directly.
     * Use this when no DI container is available.
     *
     * @param mixed $entry A class name string or an existing Middleware instance.
     * @return Middleware
     */
    public function __invoke($entry): Middleware
    {
        if (is_object($entry)) {
            return $entry;
        }

        return new $entry();
    }
}
