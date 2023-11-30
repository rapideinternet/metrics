<?php

declare(strict_types=1);

namespace Rapide\Metrics\MetricLoggers\InterfaceSpecificationTests;

use Rapide\Metrics\BaseTestCase;
use Rapide\Metrics\Exceptions\MetricsException;
use Rapide\Metrics\Interfaces\MetricLogger;
use Rapide\Metrics\ServiceCheckMetadata;
use Rapide\Metrics\ServiceStatus;

/**
 * @internal
 * @coversNothing
 */
class ServiceCheckTest extends BaseTestCase
{
    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        unlink('./log.txt');
    }

    /**
     * @dataProvider dpAllMetricLoggers
     */
    public function testMetricLoggerThrowsExceptionWhenEventMetaDataContainsTimeAsFloat(MetricLogger $metric_logger): void
    {
        $this->expectException(MetricsException::class);

        $metric_logger->serviceCheck('service_name', ServiceStatus::OK(), [ServiceCheckMetadata::TIME => 12543534.0]);
    }

    /**
     * @dataProvider dpAllMetricLoggers
     */
    public function testMetricLoggerThrowsExceptionWhenEventMetaDataContainsTimeAsString(MetricLogger $metric_logger): void
    {
        $this->expectException(MetricsException::class);

        $metric_logger->serviceCheck('service_name', ServiceStatus::OK(), [ServiceCheckMetadata::TIME => '12543534']);
    }

    /**
     * @dataProvider dpAllMetricLoggers
     */
    public function testMetricLoggerSucceedsWhenEventMetaDataContainsTimeAsInt(MetricLogger $metric_logger): void
    {
        $metric_logger->serviceCheck('service_name', ServiceStatus::OK(), [ServiceCheckMetadata::TIME => 12543534]);

        self::assertTrue(true);
    }

    /**
     * @dataProvider dpAllMetricLoggers
     */
    public function testMetricLoggerThrowsExceptionWhenEventMetaDataContainsHostnameAsInt(
        MetricLogger $metric_logger
    ): void {
        $this->expectException(MetricsException::class);

        $metric_logger->serviceCheck('service_name', ServiceStatus::OK(), [ServiceCheckMetadata::HOSTNAME => 123]);
    }

    /**
     * @dataProvider dpAllMetricLoggers
     */
    public function testMetricLoggerSucceedsWhenEventMetaDataContainsHostnameAsString(
        MetricLogger $metric_logger
    ): void {
        $metric_logger->serviceCheck(
            'service_name',
            ServiceStatus::OK(),
            [ServiceCheckMetadata::HOSTNAME => 'server01.host.com']
        );

        self::assertTrue(true);
    }
}
