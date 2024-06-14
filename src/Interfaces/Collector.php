<?php

declare(strict_types=1);

namespace Rapide\Metrics\Interfaces;

use Rapide\Metrics\Interfaces\Metrics\DistributionMetric;
use Rapide\Metrics\Interfaces\Metrics\GaugeMetric;
use Rapide\Metrics\Interfaces\Metrics\IncrementingMetric;
use Rapide\Metrics\Interfaces\Metrics\ServiceCheckMetric;
use Rapide\Metrics\Interfaces\Metrics\TimingMetric;

interface Collector
{
    public function increment(IncrementingMetric $metric): void;

    public function decrement(IncrementingMetric $metric): void;

    public function time(TimingMetric $metric): void;

    public function gauge(GaugeMetric $metric): void;

    public function distribution(DistributionMetric $metric): void;

    public function serviceCheck(ServiceCheckMetric $metric): void;

    /**
     * Attach a tag:value to all subsequent metrics.
     */
    public function attachTag(string $tag, string $value): void;

    /**
     * Return all attached tags.
     */
    public function getAttachedTags(): array;

    /**
     * Reset attached tags.
     */
    public function resetAttachedTags(): void;
}
