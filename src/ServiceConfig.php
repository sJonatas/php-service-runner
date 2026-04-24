<?php

declare(strict_types=1);

namespace ServiceRunner;

interface ServiceConfig
{
    /**
     * Returns an array of service definitions.
     *
     * Each key is a service identifier (e.g. a class name), and each value
     * is an array with the following shape:
     *
     *   [
     *       'middlewares' => [MiddlewareA::class, MiddlewareB::class, ...],
     *       'options'     => ['key' => 'value', ...],
     *   ]
     *
     * @return array<string, array{middlewares: list<string|object>, options: array}>
     */
    public function services(): array;
}
