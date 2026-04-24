<?php

declare(strict_types=1);

namespace ServiceRunner\Tests\Stub;

use ServiceRunner\Middleware\PayloadData;

readonly class SimpleData implements PayloadData
{
    public function __construct(
        public string $key = '',
        public string $name = '',
        public string $email = '',
    ) {}
}
