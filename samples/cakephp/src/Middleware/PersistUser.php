<?php

declare(strict_types=1);

namespace App\Middleware;

use Cake\Datasource\ConnectionManager;
use ServiceRunner\Middleware\Middleware;
use ServiceRunner\Middleware\Payload;

class PersistUser implements Middleware
{
    public function __invoke(Payload $payload, callable $next): Payload
    {
        $connection = ConnectionManager::get('default');

        $connection->insert('users', [
            'name'     => $payload->getAttribute('name'),
            'email'    => $payload->getAttribute('email'),
            'password' => $payload->getAttribute('password'),
        ]);

        $userId = (int) $connection->execute('SELECT last_insert_rowid()')->fetchColumn(0);

        return $next($payload->withAttribute('user_id', $userId));
    }
}
