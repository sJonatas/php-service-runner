<?php

declare(strict_types=1);

namespace App\Middleware;

use Cake\Utility\Security;
use ServiceRunner\Middleware\Middleware;
use ServiceRunner\Middleware\Payload;

class HashPassword implements Middleware
{
    public function __invoke(Payload $payload, callable $next): Payload
    {
        $hashed = password_hash($payload->getAttribute('password'), PASSWORD_BCRYPT);

        return $next($payload->withAttribute('password', $hashed));
    }
}
