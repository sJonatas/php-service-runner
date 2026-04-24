<?php

declare(strict_types=1);

namespace ServiceRunner\Tests;

use PHPUnit\Framework\TestCase;
use ServiceRunner\Middleware\BasicResolver;
use ServiceRunner\Middleware\Middleware;
use ServiceRunner\Middleware\Payload;
use ServiceRunner\Middleware\Runner;
use ServiceRunner\Middleware\ServicePayload;
use ServiceRunner\Tests\Stub\SimpleData;

class RunnerTest extends TestCase
{
    public function testRunnerExecutesMiddlewareInOrder(): void
    {
        $log = [];

        $first = new class ($log) implements Middleware {
            private array $log;
            public function __construct(array &$log) { $this->log = &$log; }
            public function __invoke(Payload $payload, callable $next): Payload
            {
                $this->log[] = 'first';
                return $next($payload->withAttribute('first', true));
            }
        };

        $second = new class ($log) implements Middleware {
            private array $log;
            public function __construct(array &$log) { $this->log = &$log; }
            public function __invoke(Payload $payload, callable $next): Payload
            {
                $this->log[] = 'second';
                return $next($payload->withAttribute('second', true));
            }
        };

        $resolver = new BasicResolver();
        $runner = new Runner([$second, $first], $resolver);

        $result = $runner(new ServicePayload());

        $this->assertSame(['first', 'second'], $log);
        $this->assertTrue($result->getAttribute('first'));
        $this->assertTrue($result->getAttribute('second'));
    }

    public function testRunnerWithEmptyQueueReturnsPayload(): void
    {
        $resolver = new BasicResolver();
        $runner = new Runner([], $resolver);

        $payload = new ServicePayload(new SimpleData(key: 'value'));
        $result = $runner($payload);

        $this->assertSame('value', $result->getAttribute('key'));
    }

    public function testMergeQueueAppendsMiddleware(): void
    {
        $log = [];

        $first = new class ($log) implements Middleware {
            private array $log;
            public function __construct(array &$log) { $this->log = &$log; }
            public function __invoke(Payload $payload, callable $next): Payload
            {
                $this->log[] = 'first';
                return $next($payload);
            }
        };

        $second = new class ($log) implements Middleware {
            private array $log;
            public function __construct(array &$log) { $this->log = &$log; }
            public function __invoke(Payload $payload, callable $next): Payload
            {
                $this->log[] = 'second';
                return $next($payload);
            }
        };

        $resolver = new BasicResolver();
        $runner = new Runner([$first], $resolver);
        $runner->mergeQueue([$second]);

        $runner(new ServicePayload());

        $this->assertSame(['second', 'first'], $log);
    }

    public function testMiddlewareCanShortCircuit(): void
    {
        $blocker = new class implements Middleware {
            public function __invoke(Payload $payload, callable $next): Payload
            {
                return $payload->withAttribute('blocked', true);
            }
        };

        $neverReached = new class implements Middleware {
            public function __invoke(Payload $payload, callable $next): Payload
            {
                return $next($payload->withAttribute('reached', true));
            }
        };

        $resolver = new BasicResolver();
        $runner = new Runner([$neverReached, $blocker], $resolver);

        $result = $runner(new ServicePayload());

        $this->assertTrue($result->getAttribute('blocked'));
        $this->assertNull($result->getAttribute('reached'));
    }
}
