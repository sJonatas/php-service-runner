<?php

declare(strict_types=1);

namespace Samples\VanillaPhp\Middleware;

use PDO;
use ServiceRunner\Middleware\Middleware;
use ServiceRunner\Middleware\Payload;

class PersistUser implements Middleware
{
    public function __construct(private PDO $pdo)
    {
    }

    public function __invoke(Payload $payload, callable $next): Payload
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $stmt->execute([
            'name'     => $payload->getAttribute('name'),
            'email'    => $payload->getAttribute('email'),
            'password' => $payload->getAttribute('password'),
        ]);

        return $next($payload->withAttribute('user_id', (int) $this->pdo->lastInsertId()));
    }
}
