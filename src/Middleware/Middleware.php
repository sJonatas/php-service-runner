<?php

declare(strict_types=1);

namespace ServiceRunner\Middleware;

interface Middleware
{
    public function __invoke(Payload $payload, callable $next): Payload;
}
