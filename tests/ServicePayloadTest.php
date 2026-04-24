<?php

declare(strict_types=1);

namespace ServiceRunner\Tests;

use PHPUnit\Framework\TestCase;
use ServiceRunner\Middleware\ServicePayload;
use ServiceRunner\Tests\Stub\SimpleData;
use ServiceRunner\Tests\Stub\TagsData;

class ServicePayloadTest extends TestCase
{
    public function testConstructorSetsAttributesFromDto(): void
    {
        $payload = new ServicePayload(new SimpleData(key: 'value'));

        $this->assertSame('value', $payload->getAttribute('key'));
    }

    public function testConstructorWithoutArguments(): void
    {
        $payload = new ServicePayload();

        $this->assertSame([], $payload->getAttributes());
    }

    public function testGetAttributeReturnsDefault(): void
    {
        $payload = new ServicePayload();

        $this->assertSame('default', $payload->getAttribute('missing', 'default'));
        $this->assertNull($payload->getAttribute('missing'));
    }

    public function testGetAttributesReturnsAll(): void
    {
        $payload = new ServicePayload(new SimpleData(key: 'k', name: 'n', email: 'e'));

        $this->assertSame(['key' => 'k', 'name' => 'n', 'email' => 'e'], $payload->getAttributes());
    }

    public function testWithAttributeIsImmutable(): void
    {
        $payload = new ServicePayload(new SimpleData(key: 'old'));
        $new = $payload->withAttribute('key', 'new');

        $this->assertNotSame($payload, $new);
        $this->assertSame('old', $payload->getAttribute('key'));
        $this->assertSame('new', $new->getAttribute('key'));
    }

    public function testWithAttributesReplacesAll(): void
    {
        $payload = new ServicePayload(new SimpleData(key: 'a'));
        $new = $payload->withAttributes(['b' => 2]);

        $this->assertSame('a', $payload->getAttribute('key'));
        $this->assertSame(['b' => 2], $new->getAttributes());
    }

    public function testWithoutAttributeRemovesKey(): void
    {
        $payload = new ServicePayload(new SimpleData(key: 'k', name: 'n'));
        $new = $payload->withoutAttribute('key');

        $this->assertSame('k', $payload->getAttribute('key'));
        $this->assertNull($new->getAttribute('key'));
        $this->assertSame('n', $new->getAttribute('name'));
    }

    public function testMergeAttributeMergesArrays(): void
    {
        $payload = new ServicePayload(new TagsData(tags: ['php']));
        $new = $payload->mergeAttribute('tags', ['laravel']);

        $this->assertSame(['php'], $payload->getAttribute('tags'));
        $this->assertSame(['php', 'laravel'], $new->getAttribute('tags'));
    }

    public function testMergeAttributeReplacesNonArray(): void
    {
        $payload = (new ServicePayload())->withAttribute('tags', 'not-array');
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
