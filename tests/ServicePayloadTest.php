<?php

declare(strict_types=1);

namespace ServiceRunner\Tests;

use PHPUnit\Framework\TestCase;
use ServiceRunner\Middleware\ServicePayload;

class ServicePayloadTest extends TestCase
{
    public function testConstructorSetsAttributes(): void
    {
        $payload = new ServicePayload(['key' => 'value']);

        $this->assertSame('value', $payload->getAttribute('key'));
    }

    public function testGetAttributeReturnsDefault(): void
    {
        $payload = new ServicePayload();

        $this->assertSame('default', $payload->getAttribute('missing', 'default'));
        $this->assertNull($payload->getAttribute('missing'));
    }

    public function testGetAttributesReturnsAll(): void
    {
        $attrs = ['a' => 1, 'b' => 2];
        $payload = new ServicePayload($attrs);

        $this->assertSame($attrs, $payload->getAttributes());
    }

    public function testWithAttributeIsImmutable(): void
    {
        $payload = new ServicePayload(['key' => 'old']);
        $new = $payload->withAttribute('key', 'new');

        $this->assertNotSame($payload, $new);
        $this->assertSame('old', $payload->getAttribute('key'));
        $this->assertSame('new', $new->getAttribute('key'));
    }

    public function testWithAttributesReplacesAll(): void
    {
        $payload = new ServicePayload(['a' => 1]);
        $new = $payload->withAttributes(['b' => 2]);

        $this->assertSame(['a' => 1], $payload->getAttributes());
        $this->assertSame(['b' => 2], $new->getAttributes());
    }

    public function testWithoutAttributeRemovesKey(): void
    {
        $payload = new ServicePayload(['a' => 1, 'b' => 2]);
        $new = $payload->withoutAttribute('a');

        $this->assertSame(['a' => 1, 'b' => 2], $payload->getAttributes());
        $this->assertSame(['b' => 2], $new->getAttributes());
    }

    public function testMergeAttributeMergesArrays(): void
    {
        $payload = new ServicePayload(['tags' => ['php']]);
        $new = $payload->mergeAttribute('tags', ['laravel']);

        $this->assertSame(['php'], $payload->getAttribute('tags'));
        $this->assertSame(['php', 'laravel'], $new->getAttribute('tags'));
    }

    public function testMergeAttributeReplacesNonArray(): void
    {
        $payload = new ServicePayload(['tags' => 'not-array']);
        $new = $payload->mergeAttribute('tags', ['laravel']);

        $this->assertSame(['laravel'], $new->getAttribute('tags'));
    }

    public function testMergeAttributeCreatesNewIfMissing(): void
    {
        $payload = new ServicePayload();
        $new = $payload->mergeAttribute('tags', ['php']);

        $this->assertSame(['php'], $new->getAttribute('tags'));
    }
}
