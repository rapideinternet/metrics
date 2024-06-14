<?php

declare(strict_types=1);

namespace Rapide\Metrics\Interfaces;

use Rapide\Metrics\Exceptions\MetricsException;
use Rapide\Metrics\ServiceStatus;

interface MetricLogger
{
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

    /**
     * Increment the value of the metric(s).
     *
     * @param string|string[] $metrics
     * @param int             $delta
     * @param string[]        $tags
     *
     * @throws MetricsException
     */
    public function increment($metrics, $delta, array $tags = []): void;

    /**
     * Decrement the value of the metric(s).
     *
     * @param string|string[] $metrics
     * @param int             $delta
     * @param string[]        $tags
     *
     * @throws MetricsException
     */
    public function decrement($metrics, $delta, array $tags = []): void;

    /**
     * Add a timing measurement to the metric(s).
     *
     * @param string|string[] $metrics
     * @param float|int       $value
     * @param string[]        $tags
     *
     * @throws MetricsException
     */
    public function timing($metrics, $value, array $tags = []): void;

    /**
     * Add a measurement of a particular thing (e.g. gas_tank.level 0.75) to the metric(s).
     *
     * @param string|string[] $metrics
     * @param float|int       $value
     * @param string[]        $tags
     *
     * @throws MetricsException
     */
    public function gauge($metrics, $value, array $tags = []): void;

    /**
     * Add a measurement to the histogram metric(s).
     *
     * Some monitoring/tracking services allow you to send values to a histogram set. DataDog does this for example:
     *   - Histograms track the statistical distribution of a set of values, like the duration of a number of database
     *   - queries or the size of files uploaded by users. Each histogram will track the average, the minimum,
     *   - the maximum, the median, the 95th percentile and the count.
     *
     * @param string|string[] $metrics
     * @param float|int       $value
     * @param string[]        $tags
     *
     * @throws MetricsException
     */
    public function histogram($metrics, $value, array $tags = []): void;

    /**
     * Add a measurement to the distribution of metric(s).
     *
     * Datadog allows you to send values to a distribution. Distributions track the statistics of metrics
     * across multiple hosts and instances and are aggregated server-side as opposed to client-side as is the
     * case with histograms.
     *
     * @param string|string[] $metrics
     * @param float|int       $value
     * @param string[]        $tags
     *
     * @throws MetricsException
     */
    public function distribution($metrics, $value, array $tags = []): void;

    /**
     * Adds the value to the metric(s).
     *
     * The 'uniqueSet', or 'set' metrics are metrics that contain a set of values that are all unique and can be used
     * to track unique website visitors for example.
     *
     * @param string|string[] $metrics
     * @param float|int       $value
     * @param string[]        $tags
     *
     * @throws MetricsException
     */
    public function uniqueSet($metrics, $value, array $tags = []): void;

    /**
     * An exception MUST be thrown when a metadata key is not allowed or contains an invalid value.
     *
     * @param string   $title
     * @param string   $text
     * @param mixed[]  $metadata
     * @param string[] $tags
     *
     * @throws MetricsException
     *
     *@see \Rapide\Metrics\EventMetadata for the allowed keys and values of the metadata array
     */
    public function event($title, $text, array $metadata = [], array $tags = []): void;

    /**
     * Track the status of a service that your application depends on.
     *
     * An exception MUST be thrown when a metadata key is not allowed or contains an invalid value.
     *
     * @param string   $name
     * @param mixed[]  $metadata
     * @param string[] $tags
     *
     * @throws MetricsException
     *
     *@see \Rapide\Metrics\ServiceCheckMetadata for the allowed keys and values of the metadata array
     */
    public function serviceCheck($name, ServiceStatus $status, array $metadata = [], array $tags = []): void;
}
