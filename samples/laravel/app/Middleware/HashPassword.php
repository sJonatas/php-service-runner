<?php

declare(strict_types=1);

namespace App\Middleware;

use Illuminate\Hashing\HashManager;
use ServiceRunner\Middleware\Middleware;
use ServiceRunner\Middleware\Payload;

class HashPassword implements Middleware
{
    public function __construct(
        private HashManager $hash,
    ) {}

    public function __invoke(Payload $payload, callable $next): Payload
    {
        $hashed = $this->hash->make($payload->getAttribute('password'));

        return $next($payload->withAttribute('password', $hashed));
    }
}
