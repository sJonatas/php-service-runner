<?php

declare(strict_types=1);

namespace App\Middleware;

use Illuminate\Support\Facades\DB;
use ServiceRunner\Middleware\Middleware;
use ServiceRunner\Middleware\Payload;

class PersistUser implements Middleware
{
    public function __invoke(Payload $payload, callable $next): Payload
    {
        $userId = DB::table('users')->insertGetId([
            'name'     => $payload->getAttribute('name'),
            'email'    => $payload->getAttribute('email'),
            'password' => $payload->getAttribute('password'),
        ]);

        return $next($payload->withAttribute('user_id', $userId));
    }
}
