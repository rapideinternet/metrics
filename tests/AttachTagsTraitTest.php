<?php

declare(strict_types=1);

namespace Rapide\Metrics;

use Rapide\Metrics\MetricLoggers\AttachTags;

/**
 * @covers \AttachTags
 *
 * @internal
 */
class AttachTagsTraitTest extends BaseTestCase
{
    use AttachTags;

    public function testSetterAndGetter(): void
    {
        $this->attachTag('test', 'value');
        self::assertEquals(['test' => 'value'], $this->tags);

        $returnedValue = $this->getAttachedTags();
        self::assertEquals(['test' => 'value'], $returnedValue);
    }

    public function testResetTags(): void
    {
        $this->attachTag('test', 'value');
        $this->resetAttachedTags();
        self::assertEmpty($this->getAttachedTags());
    }

    public function testMergeTags(): void
    {
        $this->attachTag('test', 'value');
        $this->attachTag('override-me', 'not-overridden');
        $this->attachTag('override-me-too', 'not-overridden');

        $tags = [
            'actual-tag'      => 'actual-value',
            'actual-tag-too'  => '',
            'override-me'     => null,
            'override-me-too' => 0,
        ];

        $expected = [
            'test'            => 'value',
            'actual-tag'      => 'actual-value',
            'actual-tag-too'  => '',
            'override-me-too' => 0,
        ];

        $actual = $this->getMergedTags($tags);

        self::assertEquals($expected, $actual);
    }
}
