<?php

declare(strict_types=1);

namespace ServiceRunner\Middleware;

interface MiddlewareResolver
{
    /**
     * Converts a middleware queue entry to an implementation of
     * Middleware interface.
     *
     * @param mixed $entry The middleware queue entry.
     *
     * @return Middleware
     */
    public function __invoke($entry): Middleware;
}
