<?php

declare(strict_types=1);

namespace ServiceRunner\Tests;

use PHPUnit\Framework\TestCase;
use ServiceRunner\Middleware\BasicResolver;
use ServiceRunner\Middleware\Middleware;
use ServiceRunner\Middleware\Payload;
use ServiceRunner\Middleware\Runner;
use ServiceRunner\Middleware\ServicePayload;
use ServiceRunner\Service;
use ServiceRunner\Tests\Stub\OptionsData;
use ServiceRunner\Tests\Stub\SimpleData;

class ServiceTest extends TestCase
{
    public function testServiceRunsMiddlewarePipeline(): void
    {
        $middleware = new class implements Middleware {
            public function __invoke(Payload $payload, callable $next): Payload
            {
                return $next($payload->withAttribute('processed', true));
            }
        };

        $resolver = new BasicResolver();
        $runner = new Runner([$middleware], $resolver);

        $service = new class([], $runner) extends Service {};

        $result = $service->run(new ServicePayload(new SimpleData(name: 'test')));

        $this->assertNotNull($result);
        $this->assertTrue($result->getAttribute('processed'));
        $this->assertSame('test', $result->getAttribute('name'));
    }

    public function testServiceMergesOptions(): void
    {
        $middleware = new class implements Middleware {
            public function __invoke(Payload $payload, callable $next): Payload
            {
                return $next($payload);
            }
        };

        $resolver = new BasicResolver();
        $runner = new Runner([$middleware], $resolver);

        $service = new class(['notify' => true], $runner) extends Service {};

        $result = $service->run(new ServicePayload());

        $this->assertNotNull($result);
        $this->assertTrue($result->getAttribute('options')['notify']);
    }

    public function testShouldProcessReturnsFalseSkipsExecution(): void
    {
        $middleware = new class implements Middleware {
            public function __invoke(Payload $payload, callable $next): Payload
            {
                return $next($payload->withAttribute('processed', true));
            }
        };

        $resolver = new BasicResolver();
        $runner = new Runner([$middleware], $resolver);

        $service = new class([], $runner) extends Service {
            public function shouldProcess(Payload $payload): bool
            {
                return false;
            }
        };

        $result = $service->run(new ServicePayload());

        $this->assertNull($result);
    }

    public function testPayloadOptionsAreMergedWithServiceOptions(): void
    {
        $middleware = new class implements Middleware {
            public function __invoke(Payload $payload, callable $next): Payload
            {
                return $next($payload);
            }
        };

        $resolver = new BasicResolver();
        $runner = new Runner([$middleware], $resolver);

        $service = new class(['a' => 1], $runner) extends Service {};

        $result = $service->run(new ServicePayload(new OptionsData(options: ['b' => 2])));

        $this->assertNotNull($result);
        $options = $result->getAttribute('options');
        $this->assertSame(1, $options['a']);
        $this->assertSame(2, $options['b']);
    }
}
