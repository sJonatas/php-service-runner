<?php

declare(strict_types=1);

namespace ServiceRunner\Middleware;

/**
 * Marker interface for payload DTOs.
 *
 * Implement this on a readonly class with public promoted constructor
 * properties. ServicePayload will extract them automatically.
 *
 *     readonly class CreateUserData implements PayloadData
 *     {
 *         public function __construct(
 *             public string $name,
 *             public string $email,
 *         ) {}
 *     }
 */
interface PayloadData
{
}
