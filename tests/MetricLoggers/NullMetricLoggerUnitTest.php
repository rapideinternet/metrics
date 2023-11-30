<?php

declare(strict_types=1);

namespace Rapide\Metrics\MetricLoggers;

use Rapide\Metrics\BaseTestCase;
use Rapide\Metrics\Interfaces\MetricLogger;
use Rapide\Metrics\ServiceStatus;
use Rapide\Metrics\Transformers\EventMetadataSanitizer;
use Rapide\Metrics\Transformers\ServiceCheckMetadataSanitizer;

/**
 * @covers \NullMetricLogger
 *
 * @internal
 */
class NullMetricLoggerUnitTest extends BaseTestCase
{
    public function testNullMetricLoggerIsInstantiable(): void
    {
        $nullMetricLogger = new NullMetricLogger(
            new EventMetadataSanitizer(),
            new ServiceCheckMetadataSanitizer()
        );

        self::assertInstanceOf(MetricLogger::class, $nullMetricLogger);

        $nullMetricLogger->increment('my.path', 2, ['tag']);
        $nullMetricLogger->decrement('my.path', 2, ['tag']);
        $nullMetricLogger->gauge('my.path', 2, ['tag']);
        $nullMetricLogger->timing('my.path', 2, ['tag']);
        $nullMetricLogger->histogram('my.path', 2, ['tag']);
        $nullMetricLogger->distribution('my.path', 2, ['tag']);
        $nullMetricLogger->uniqueSet('my.path', 2, ['tag']);
        $nullMetricLogger->event('title', 'text');
        $nullMetricLogger->serviceCheck('service', ServiceStatus::OK());
    }
}
