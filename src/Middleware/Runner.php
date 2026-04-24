<?php

declare(strict_types=1);

namespace ServiceRunner\Middleware;

/**
 * Class Runner.
 */
class Runner implements MiddlewareRunner
{
    /**
     * @var array
     */
    protected $queue;

    /**
     * @var callable|MiddlewareResolver
     */
    protected $resolver;

    /**
     * Runner constructor.
     * @param array $queue
     * @param callable $resolver
     */
    public function __construct(array $queue, callable $resolver)
    {
        $this->queue = $queue;
        $this->resolver = $resolver;
    }

    /**
     * Calls the next entry in the queue.
     *
     * @param Payload $payload
     *
     * @return Payload
     */
    public function __invoke(Payload $payload): Payload
    {
        $entry = ! empty($this->queue) ? array_pop($this->queue) : null;
        $middleware = $this->resolve($entry);

        return $middleware($payload, $this);
    }

    public function mergeQueue(array $queue)
    {
        $this->queue = array_merge($this->queue, $queue);
    }

    /**
     * Converts a queue entry to a callable, using the resolver if present.
     *
     * @param mixed|callable|Middleware $entry the queue entry.
     *
     * @return callable|Middleware
     */
    protected function resolve($entry)
    {
        if (! $entry) {
            return static function (Payload $payload, callable $next) {
                return $payload;
            };
        }
        if (! $this->resolver) {
            return $entry;
        }

        return call_user_func($this->resolver, $entry);
    }
}
