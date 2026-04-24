<?php

declare(strict_types=1);

namespace ServiceRunner\Tests\Stub;

use ServiceRunner\Middleware\PayloadData;

readonly class TagsData implements PayloadData
{
    public function __construct(
        public array $tags = [],
    ) {}
}
