<?php

declare(strict_types=1);

namespace App\Service;

use ServiceRunner\Middleware\Payload;
use ServiceRunner\Service;

class CreateUserService extends Service
{
    public function shouldProcess(Payload $payload): bool
    {
        return $payload->getAttribute('email') !== null;
    }
}
