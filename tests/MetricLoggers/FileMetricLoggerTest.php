<?php

declare(strict_types=1);

namespace Rapide\Metrics\MetricLoggers;

use Rapide\Metrics\BaseTestCase;
use Rapide\Metrics\Transformers\EventMetadataSanitizer;
use Rapide\Metrics\Transformers\ServiceCheckMetadataSanitizer;

/**
 * @covers \FileMetricLoggerTest
 *
 * @internal
 */
class FileMetricLoggerTest extends BaseTestCase
{
    public function testFileMetricLoggerIsInstantiable(): void
    {
        self::assertNotNull(
            new FileMetricLogger(
                new EventMetadataSanitizer(),
                new ServiceCheckMetadataSanitizer(),
                "this value doesn't matter"
            )
        );
    }
}
