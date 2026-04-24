<?php

declare(strict_types=1);

namespace ServiceRunner\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use ServiceRunner\Middleware\Middleware;
use ServiceRunner\Middleware\Payload;
use ServiceRunner\Middleware\Resolver;

class ResolverTest extends TestCase
{
    public function testResolvesByClassName(): void
    {
        $middleware = $this->createMock(Middleware::class);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with('App\Middleware\SomeMiddleware')
            ->willReturn($middleware);

        $resolver = new Resolver($container);

        $result = ($resolver)('App\Middleware\SomeMiddleware');

        $this->assertSame($middleware, $result);
    }

    public function testReturnsObjectDirectly(): void
    {
        $middleware = $this->createMock(Middleware::class);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->never())->method('get');

        $resolver = new Resolver($container);

        $result = ($resolver)($middleware);

        $this->assertSame($middleware, $result);
    }
}
