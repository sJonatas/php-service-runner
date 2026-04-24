<?php

declare(strict_types=1);

namespace ServiceRunner\Middleware;

interface Payload
{
    /**
     * Retrieve a single derived payload attribute.
     *
     * Retrieves a single derived payload attribute as described in
     * getAttributes(). If the attribute has not been previously set, returns
     * the default value as provided.
     *
     * This method obviates the need for a hasAttribute() method, as it allows
     * specifying a default value to return if the attribute is not found.
     *
     * @see getAttributes()
     *
     * @param string $name The attribute name.
     * @param mixed $default Default value to return if the attribute does not exist.
     *
     * @return mixed
     */
    public function getAttribute($name, $default = null);
    /**
     * Retrieve attributes derived from the process.
     *
     * The request "attributes" may be used to allow injection of any
     * parameters derived from the process and CAN be mutable.
     *
     * @return array Attributes derived from the request.
     */
    public function getAttributes(): array;
    /**
     * Return an instance with the specified derived payload attribute.
     *
     * This method allows setting a single derived payload attribute as
     * described in getAttributes().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated attribute.
     *
     * @param string $name The attribute name.
     * @param mixed $value The value of the attribute.
     *
     * @return Payload
     * @see getAttributes()
     */
    public function withAttribute($name, $value): self;
    /**
     * Return an instance with the specified derived payload attributes.
     *
     * This method allows setting multiple derived payload attribute as
     * described in getAttributes().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated attributes.
     *
     * @param array $attributes
     *
     * @return Payload
     * @see getAttributes()
     */
    public function withAttributes(array $attributes): self;

    /**
     * Return an instance that removes the specified derived payload attribute.
     *
     * This method allows removing a single derived payload attribute as
     * described in getAttributes().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that removes
     * the attribute.
     *
     * @param string $name The attribute name.
     *
     * @return Payload
     * @see getAttributes()
     */
    public function withoutAttribute($name): self;
}
