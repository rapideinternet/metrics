<?php

declare(strict_types=1);

namespace Rapide\Metrics\MetricLoggers;

use PHPUnit\Framework\MockObject\MockObject;
use Rapide\Metrics\BaseTestCase;
use Rapide\Metrics\ServiceStatus;

/**
 * @covers \CompositeMetricLoggerTest
 *
 * @internal
 */
class CompositeMetricLoggerTest extends BaseTestCase
{
    public function testCompositeMetricLoggerCallsAllChildLoggers(): void
    {
        /** @var NullMetricLogger&MockObject $logger1 */
        $logger1 = self::createMock(NullMetricLogger::class);
        /** @var FileMetricLogger&MockObject $logger2 */
        $logger2               = self::createMock(FileMetricLogger::class);
        $compositeMetricLogger = new CompositeMetricLogger();

        $compositeMetricLogger->addMetricLogger($logger1);
        $compositeMetricLogger->addMetricLogger($logger2);

        $logger1->expects(self::once())->method('increment')->with('my.path', 2, ['tag']);
        $logger2->expects(self::once())->method('increment')->with('my.path', 2, ['tag']);
        $compositeMetricLogger->increment('my.path', 2, ['tag']);

        $logger1->expects(self::once())->method('decrement')->with('my.path', 2, ['tag']);
        $logger2->expects(self::once())->method('decrement')->with('my.path', 2, ['tag']);
        $compositeMetricLogger->decrement('my.path', 2, ['tag']);

        $logger1->expects(self::once())->method('timing')->with('my.path', 2, ['tag']);
        $logger2->expects(self::once())->method('timing')->with('my.path', 2, ['tag']);
        $compositeMetricLogger->timing('my.path', 2, ['tag']);

        $logger1->expects(self::once())->method('gauge')->with('my.path', 2, ['tag']);
        $logger2->expects(self::once())->method('gauge')->with('my.path', 2, ['tag']);
        $compositeMetricLogger->gauge('my.path', 2, ['tag']);

        $logger1->expects(self::once())->method('histogram')->with('my.path', 2, ['tag']);
        $logger2->expects(self::once())->method('histogram')->with('my.path', 2, ['tag']);
        $compositeMetricLogger->histogram('my.path', 2, ['tag']);

        $logger1->expects(self::once())->method('distribution')->with('my.path', 2, ['tag']);
        $logger2->expects(self::once())->method('distribution')->with('my.path', 2, ['tag']);
        $compositeMetricLogger->distribution('my.path', 2, ['tag']);

        $logger1->expects(self::once())->method('uniqueSet')->with('my.path', 2, ['tag']);
        $logger2->expects(self::once())->method('uniqueSet')->with('my.path', 2, ['tag']);
        $compositeMetricLogger->uniqueSet('my.path', 2, ['tag']);

        $logger1->expects(self::once())->method('event')->with('title', 'text', ['META' => '1'], ['tag']);
        $logger2->expects(self::once())->method('event')->with('title', 'text', ['META' => '1'], ['tag']);
        $compositeMetricLogger->event('title', 'text', ['META' => '1'], ['tag']);

        $logger1->expects(self::once())->method('serviceCheck')->with('service', ServiceStatus::OK(), ['META' => '1'], ['tag']);
        $logger2->expects(self::once())->method('serviceCheck')->with('service', ServiceStatus::OK(), ['META' => '1'], ['tag']);
        $compositeMetricLogger->serviceCheck('service', ServiceStatus::OK(), ['META' => '1'], ['tag']);
    }
}
