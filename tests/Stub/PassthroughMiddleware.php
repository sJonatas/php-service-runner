<?php

declare(strict_types=1);

namespace ServiceRunner\Tests\Stub;

use ServiceRunner\Middleware\Middleware;
use ServiceRunner\Middleware\Payload;

class PassthroughMiddleware implements Middleware
{
    public function __invoke(Payload $payload, callable $next): Payload
    {
        return $next($payload->withAttribute('passthrough', true));
    }
}
