<?php

declare(strict_types=1);

namespace ServiceRunner;

use ServiceRunner\Middleware\MiddlewareRunner;
use ServiceRunner\Middleware\Payload;

abstract class Service
{
    /**
     * @var array
     */
    protected $options;
    /**
     * @var MiddlewareRunner
     */
    protected $runner;

    public function __construct(array $options, MiddlewareRunner $runner)
    {
        $this->options = $options;
        $this->runner = $runner;
    }

    /**
     * @param Payload $payload
     * @return Payload|null
     */
    public function run(Payload $payload): ?Payload
    {
        $runner = $this->runner;

        $options = $payload->getAttribute('options', []);

        if (! $this->shouldProcess($payload)) {
            return null;
        }

        return $runner($payload->withAttribute('options', array_merge($this->options, $options)));
    }

    public function shouldProcess(Payload $payload)
    {
        return true;
    }
}
