<?php

declare(strict_types=1);

namespace Rapide\Metrics;

use DataDog\DogStatsd;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use Rapide\Metrics\Interfaces\MetricLogger;
use Rapide\Metrics\MetricLoggers\DogStatsD\DogStatsDMetricLogger;
use Rapide\Metrics\MetricLoggers\DogStatsD\EventMetadataTransformer;
use Rapide\Metrics\MetricLoggers\DogStatsD\Interfaces\DogStatsDConfiguration;
use Rapide\Metrics\MetricLoggers\DogStatsD\ServiceCheckMetadataTransformer;
use Rapide\Metrics\MetricLoggers\FileMetricLogger;
use Rapide\Metrics\MetricLoggers\LogMetricLogger;
use Rapide\Metrics\MetricLoggers\NullMetricLogger;
use Rapide\Metrics\Transformers\EventMetadataSanitizer;
use Rapide\Metrics\Transformers\ServiceCheckMetadataSanitizer;

/**
 * @internal
 * @coversNothing
 */
class BaseTestCase extends TestCase
{
    /**
     * @return array<class-string, list<MetricLogger>>
     */
    public function dpAllMetricLoggers(): array
    {
        $eventMetadataSanitizer        = new EventMetadataSanitizer();
        $serviceCheckMetadataSanitizer = new ServiceCheckMetadataSanitizer();

        $dogStatsDMetricLogger = new DogStatsDMetricLogger(
            self::createMock(DogStatsDConfiguration::class),
            self::createMock(DogStatsd::class),
            new EventMetadataTransformer($eventMetadataSanitizer),
            new ServiceCheckMetadataTransformer($serviceCheckMetadataSanitizer)
        );

        $nullMetricLogger = new NullMetricLogger(
            $eventMetadataSanitizer,
            $serviceCheckMetadataSanitizer
        );

        $fileMetricLogger = new FileMetricLogger(
            $eventMetadataSanitizer,
            $serviceCheckMetadataSanitizer,
            'log.txt'
        );

        $logMetricLogger = new LogMetricLogger(
            $eventMetadataSanitizer,
            $serviceCheckMetadataSanitizer,
            new TestLogger()
        );

        return [
            get_class($dogStatsDMetricLogger) => [$dogStatsDMetricLogger],
            get_class($nullMetricLogger)      => [$nullMetricLogger],
            get_class($fileMetricLogger)      => [$fileMetricLogger],
            get_class($logMetricLogger)       => [$logMetricLogger],
        ];
    }
}
