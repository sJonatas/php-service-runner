<?php

declare(strict_types=1);

namespace ServiceRunner\Middleware;


class ServicePayload implements Payload
{
    /**
     * @var array|null
     */
    protected $attributes;

    /**
     * Payload constructor.
     *
     * @param array|null $attributes
     */
    public function __construct(?array $attributes = null)
    {
        $this->attributes = $attributes ?? [];
    }

    /**
     * @inheritDoc
     */
    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @inheritDoc
     */
    public function withAttribute($name, $value): Payload
    {
        $cloned = clone $this;
        $cloned->attributes[$name] = $value;

        return $cloned;
    }

    /**
     * @inheritDoc
     */
    public function withAttributes(array $attributes): Payload
    {
        $cloned = clone $this;
        $cloned->attributes = $attributes;

        return $cloned;
    }

    /**
     * @inheritDoc
     */
    public function withoutAttribute($name): Payload
    {
        $cloned = clone $this;
        unset($cloned->attributes[$name]);

        return $cloned;
    }

    public function mergeAttribute($name, array $value)
    {
        $attribute = $this->getAttribute($name, []);

        if (! is_array($attribute)) {
            return $this->withAttribute($name, $value);
        }

        $cloned = clone $this;
        $cloned->attributes[$name] = array_merge($attribute, $value);

        return $cloned;
    }
}
