<?php

declare(strict_types=1);

namespace ServiceRunner\Middleware;

interface MiddlewareRunner
{
    /**
     * Calls the next entry in the queue.
     *
     * @param Payload $payload
     *
     * @return Payload
     */
    public function __invoke(Payload $payload): Payload;
}
