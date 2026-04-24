<?php

declare(strict_types=1);

namespace ServiceRunner\Tests;

use PHPUnit\Framework\TestCase;
use ServiceRunner\Middleware\BasicResolver;
use ServiceRunner\Middleware\Middleware;
use ServiceRunner\Middleware\Payload;
use ServiceRunner\Tests\Stub\PassthroughMiddleware;

class BasicResolverTest extends TestCase
{
    public function testResolvesClassName(): void
    {
        $resolver = new BasicResolver();

        $result = ($resolver)(PassthroughMiddleware::class);

        $this->assertInstanceOf(PassthroughMiddleware::class, $result);
    }

    public function testReturnsObjectDirectly(): void
    {
        $middleware = $this->createMock(Middleware::class);
        $resolver = new BasicResolver();

        $result = ($resolver)($middleware);

        $this->assertSame($middleware, $result);
    }
}
