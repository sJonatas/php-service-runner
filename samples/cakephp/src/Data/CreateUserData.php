<?php

declare(strict_types=1);

namespace App\Data;

use ServiceRunner\Middleware\PayloadData;

readonly class CreateUserData implements PayloadData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {}
}
