<?php

declare(strict_types=1);

namespace Rapide\Metrics;

use Rapide\Metrics\Exceptions\MetricsException;
use Rapide\Metrics\Interfaces\Collector;
use Rapide\Metrics\Interfaces\MetricLogger;
use Rapide\Metrics\Interfaces\Metrics\DistributionMetric;
use Rapide\Metrics\Interfaces\Metrics\GaugeMetric;
use Rapide\Metrics\Interfaces\Metrics\IncrementingMetric;
use Rapide\Metrics\Interfaces\Metrics\ServiceCheckMetric;
use Rapide\Metrics\Interfaces\Metrics\TimingMetric;

final class MetricCollector implements Collector
{
    private MetricLogger $metricLogger;

    public function __construct(MetricLogger $metricLogger)
    {
        $this->metricLogger = $metricLogger;
    }

    /**
     * @throws MetricsException
     */
    public function increment(IncrementingMetric $metric): void
    {
        $this->metricLogger->increment(
            $metric->metricPath(),
            $metric->delta(),
            $metric->tags()
        );
    }

    /**
     * @throws MetricsException
     */
    public function decrement(IncrementingMetric $metric): void
    {
        $this->metricLogger->decrement(
            $metric->metricPath(),
            $metric->delta(),
            $metric->tags()
        );
    }

    /**
     * @throws MetricsException
     */
    public function gauge(GaugeMetric $metric): void
    {
        $this->metricLogger->gauge(
            $metric->metricPath(),
            $metric->value(),
            $metric->tags()
        );
    }

    /**
     * @throws MetricsException
     */
    public function time(TimingMetric $metric): void
    {
        $this->metricLogger->timing(
            $metric->metricPath(),
            $metric->value(),
            $metric->tags()
        );
    }

    /**
     * @throws MetricsException
     */
    public function distribution(DistributionMetric $metric): void
    {
        $this->metricLogger->distribution(
            $metric->metricPath(),
            $metric->value(),
            $metric->tags()
        );
    }

    /**
     * @throws MetricsException
     */
    public function serviceCheck(ServiceCheckMetric $metric): void
    {
        $this->metricLogger->serviceCheck(
            $metric->metricPath(),
            $metric->status(),
            $metric->metadata(),
            $metric->tags(),
        );
    }

    public function attachTag(string $tag, string $value): void
    {
        $this->metricLogger->attachTag($tag, $value);
    }

    public function getAttachedTags(): array
    {
        return $this->metricLogger->getAttachedTags();
    }

    public function resetAttachedTags(): void
    {
        $this->metricLogger->resetAttachedTags();
    }
}
