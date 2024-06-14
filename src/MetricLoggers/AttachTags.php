<?php

declare(strict_types=1);

namespace Rapide\Metrics\MetricLoggers;

use function array_filter;
use function array_merge;

trait AttachTags
{
    /** @var array<string, string> */
    private array $tags = [];

    public function attachTag(string $tag, string $value): void
    {
        $this->tags[$tag] = $value;
    }

    /** @return array<string, string> */
    public function getAttachedTags(): array
    {
        return $this->tags;
    }

    public function resetAttachedTags(): void
    {
        $this->tags = [];
    }

    /** @return array<string, string> */
    protected function getMergedTags(array $tags): array
    {
        $isNotNull = static function ($value) {
            return $value !== null;
        };

        return array_filter(array_merge($this->tags, $tags), $isNotNull);
    }
}
