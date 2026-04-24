<?php

declare(strict_types=1);

namespace Samples\VanillaPhp\Middleware;

use ServiceRunner\Middleware\Middleware;
use ServiceRunner\Middleware\Payload;

class ValidateEmail implements Middleware
{
    public function __invoke(Payload $payload, callable $next): Payload
    {
        $email = $payload->getAttribute('email');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $payload->withAttribute('error', 'Invalid email address');
        }

        return $next($payload);
    }
}
