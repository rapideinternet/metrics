<?php

declare(strict_types=1);

namespace Rapide\Metrics\MetricLoggers\InterfaceSpecificationTests;

use Rapide\Metrics\BaseTestCase;
use Rapide\Metrics\EventMetadata;
use Rapide\Metrics\EventPriority;
use Rapide\Metrics\EventSeverity;
use Rapide\Metrics\Exceptions\MetricsException;
use Rapide\Metrics\Interfaces\MetricLogger;

/**
 * @internal
 * @coversNothing
 */
class EventTest extends BaseTestCase
{
    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        unlink('./log.txt');
    }

    /**
     * @dataProvider dpAllMetricLoggers
     */
    public function testMetricLoggerThrowsExceptionWhenEventMetaDataContainsTimeAsFloat(MetricLogger $metricLogger): void
    {
        $this->expectException(MetricsException::class);

        $metricLogger->event('even_title', 'text', [EventMetadata::TIME => 12543534.0]);
    }

    /**
     * @dataProvider dpAllMetricLoggers
     */
    public function testMetricLoggerThrowsExceptionWhenEventMetaDataContainsTimeAsString(MetricLogger $metricLogger): void
    {
        $this->expectException(MetricsException::class);

        $metricLogger->event('even_title', 'text', [EventMetadata::TIME => '12543534']);
    }

    /**
     * @dataProvider dpAllMetricLoggers
     */
    public function testMetricLoggerSucceedsWhenEventMetaDataContainsTimeAsInt(MetricLogger $metricLogger): void
    {
        $metricLogger->event('even_title', 'text', [EventMetadata::TIME => 12543534]);

        self::assertTrue(true);
    }

    /**
     * @dataProvider dpAllMetricLoggers
     */
    public function testMetricLoggerThrowsExceptionWhenEventMetaDataContainsAggregationKeyAsInt(
        MetricLogger $metricLogger
    ): void {
        $this->expectException(MetricsException::class);

        $metricLogger->event('even_title', 'text', [EventMetadata::AGGREGATION_GROUP => 123]);
    }

    /**
     * @dataProvider dpAllMetricLoggers
     */
    public function testMetricLoggerSucceedsWhenEventMetaDataContainsAggregationKeyAsString(
        MetricLogger $metricLogger
    ): void {
        $metricLogger->event('even_title', 'text', [EventMetadata::AGGREGATION_GROUP => 'some_key']);

        self::assertTrue(true);
    }

    /**
     * @dataProvider dpAllMetricLoggers
     */
    public function testMetricLoggerThrowsExceptionWhenEventMetaDataContainsHostnameAsInt(
        MetricLogger $metricLogger
    ): void {
        $this->expectException(MetricsException::class);

        $metricLogger->event('even_title', 'text', [EventMetadata::HOSTNAME => 123]);
    }

    /**
     * @dataProvider dpAllMetricLoggers
     */
    public function testMetricLoggerSucceedsWhenEventMetaDataContainsHostnameAsString(
        MetricLogger $metricLogger
    ): void {
        $metricLogger->event('even_title', 'text', [EventMetadata::HOSTNAME => 'server01.host.com']);

        self::assertTrue(true);
    }

    /**
     * @dataProvider dpAllMetricLoggers
     */
    public function testMetricLoggerThrowsExceptionWhenEventMetaDataContainsPriorityAsInt(
        MetricLogger $metricLogger
    ): void {
        $this->expectException(MetricsException::class);

        $metricLogger->event('even_title', 'text', [EventMetadata::PRIORITY => 0]);
    }

    /**
     * @dataProvider dpAllMetricLoggers
     */
    public function testMetricLoggerThrowsExceptionWhenEventMetaDataContainsPriorityAsString(
        MetricLogger $metricLogger
    ): void {
        $this->expectException(MetricsException::class);

        $metricLogger->event('even_title', 'text', [EventMetadata::PRIORITY => 'low']);
    }

    /**
     * @dataProvider dpAllMetricLoggers
     */
    public function testMetricLoggerSucceedsWhenEventMetaDataContainsPriorityAsEnumObject(
        MetricLogger $metricLogger
    ): void {
        $metricLogger->event('even_title', 'text', [EventMetadata::PRIORITY => EventPriority::LOW()]);
        $metricLogger->event('even_title', 'text', [EventMetadata::PRIORITY => EventPriority::HIGH()]);

        self::assertTrue(true);
    }

    /**
     * @dataProvider dpAllMetricLoggers
     */
    public function testMetricLoggerThrowsExceptionWhenEventMetaDataContainsSeverityAsInt(
        MetricLogger $metricLogger
    ): void {
        $this->expectException(MetricsException::class);

        $metricLogger->event('even_title', 'text', [EventMetadata::SEVERITY => 0]);
    }

    /**
     * @dataProvider dpAllMetricLoggers
     */
    public function testMetricLoggerThrowsExceptionWhenEventMetaDataContainsSeverityAsString(
        MetricLogger $metricLogger
    ): void {
        $this->expectException(MetricsException::class);

        $metricLogger->event('even_title', 'text', [EventMetadata::SEVERITY => 'low']);
    }

    /**
     * @dataProvider dpAllMetricLoggers
     */
    public function testMetricLoggerSucceedsWhenEventMetaDataContainsSeverityAsEnumObject(
        MetricLogger $metricLogger
    ): void {
        $metricLogger->event('even_title', 'text', [EventMetadata::SEVERITY => EventSeverity::SUCCESS()]);
        $metricLogger->event('even_title', 'text', [EventMetadata::SEVERITY => EventSeverity::INFO()]);
        $metricLogger->event('even_title', 'text', [EventMetadata::SEVERITY => EventSeverity::WARNING()]);
        $metricLogger->event('even_title', 'text', [EventMetadata::SEVERITY => EventSeverity::ERROR()]);

        self::assertTrue(true);
    }
}
