<?php

declare(strict_types=1);

namespace Rapide\Metrics\MetricLoggers\DogStatsD;

use DataDog\DogStatsd;
use PHPUnit\Framework\MockObject\MockObject;
use Rapide\Metrics\BaseTestCase;
use Rapide\Metrics\EventMetadata;
use Rapide\Metrics\EventPriority;
use Rapide\Metrics\EventSeverity;
use Rapide\Metrics\Exceptions\MetricsException;
use Rapide\Metrics\MetricLoggers\DogStatsD\Interfaces\DogStatsDConfiguration;
use Rapide\Metrics\ServiceCheckMetadata;
use Rapide\Metrics\ServiceStatus;
use Rapide\Metrics\Transformers\EventMetadataSanitizer;
use Rapide\Metrics\Transformers\ServiceCheckMetadataSanitizer;

/**
 * @internal
 * @coversNothing
 */
class DogStatsDMetricLoggerUnitTest extends BaseTestCase
{
    /** @var DogStatsDConfiguration&MockObject */
    private $configuration;

    /** @var DogStatsd&MockObject */
    private $client;

    private DogStatsDMetricLogger $logger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configuration = self::createMock(DogStatsDConfiguration::class);

        $this->configuration->method('getNamespace')->willReturn('my_namespace');
        $this->configuration->method('getPort')->willReturn(12345);
        $this->configuration->method('getHost')->willReturn('my_host');
        $this->configuration->method('getTimeout')->willReturn(5.0);
        $this->configuration->method('getDataDogHost')->willReturn('my_host');

        $this->client = self::createMock(DogStatsd::class);

        $this->logger = new DogStatsDMetricLogger(
            $this->configuration,
            $this->client,
            new EventMetadataTransformer(new EventMetadataSanitizer()),
            new ServiceCheckMetadataTransformer(new ServiceCheckMetadataSanitizer())
        );
    }

    public function testDogStatsDClientIsCalledCorrectlyWhenIncrementingAndDecrementing(): void
    {
        $this->client->expects(self::once())->method('increment')->with(['metric_1', 'metric_2'], 15, ['tag1', 'tag2']);
        $this->logger->increment(['metric_1', 'metric_2'], 15, ['tag1', 'tag2']);

        $this->client->expects(self::once())->method('increment')->with('metric_1', 15, ['tag1', 'tag2']);
        $this->logger->increment('metric_1', 15, ['tag1', 'tag2']);

        $this->client->expects(self::once())->method('decrement')->with('metric_1', 15, ['tag1', 'tag2']);
        $this->logger->decrement(['metric_1', 'metric_2'], 15, ['tag1', 'tag2']);

        $this->client->expects(self::once())->method('decrement')->with('metric_1', 15, ['tag1', 'tag2']);
        $this->logger->decrement('metric_1', 15, ['tag1', 'tag2']);
    }

    public function testDogStatsDClientIsCalledCorrectlyWheSendingGauge(): void
    {
        $this->logger->gauge('metric_1', 15, ['tag1', 'tag2']);
//        \Phake::verify($this->client)->gauge('metric_1', 15, ['tag1', 'tag2']);

        $this->logger->gauge(['metric_2', 'metric_3'], 15, ['tag1', 'tag2']);
//        \Phake::verify($this->client)->gauge('metric_2', 15, ['tag1', 'tag2']);
//        \Phake::verify($this->client)->gauge('metric_3', 15, ['tag1', 'tag2']);
    }

    public function testDogStatsDClientIsCalledCorrectlyWhenSendingTiming(): void
    {
        $this->logger->timing('metric_1', 15, ['tag1', 'tag2']);
//        \Phake::verify($this->client)->timing('metric_1', 15, ['tag1', 'tag2']);

        $this->logger->timing(['metric_2', 'metric_3'], 15, ['tag1', 'tag2']);
//        \Phake::verify($this->client)->timing('metric_2', 15, ['tag1', 'tag2']);
//        \Phake::verify($this->client)->timing('metric_3', 15, ['tag1', 'tag2']);
    }

    public function testDogStatsDClientIsCalledCorrectlyWhenSendingHistogram(): void
    {
        $this->logger->histogram('metric_1', 15, ['tag1', 'tag2']);
//        \Phake::verify($this->client)->histogram('metric_1', 15, 1.0, ['tag1', 'tag2']);

        $this->logger->histogram(['metric_2', 'metric_3'], 15, ['tag1', 'tag2']);
//        \Phake::verify($this->client)->histogram('metric_2', 15, 1.0, ['tag1', 'tag2']);
//        \Phake::verify($this->client)->histogram('metric_3', 15, 1.0, ['tag1', 'tag2']);
    }

    public function testDogStatsDClientIsCalledCorrectlyWhenSendingDistribution(): void
    {
        $this->logger->distribution('metric_1', 15, ['tag1', 'tag2']);
//        \Phake::verify($this->client)->distribution('metric_1', 15, 1.0, ['tag1', 'tag2']);

        $this->logger->distribution(['metric_2', 'metric_3'], 15, ['tag1', 'tag2']);
//        \Phake::verify($this->client)->distribution('metric_2', 15, 1.0, ['tag1', 'tag2']);
//        \Phake::verify($this->client)->distribution('metric_3', 15, 1.0, ['tag1', 'tag2']);
    }

    public function testDogStatsDClientIsCalledCorrectlyWhenSendingUniqueSet(): void
    {
        $this->logger->uniqueSet('metric_1', 15, ['tag1', 'tag2']);
//        \Phake::verify($this->client)->set('metric_1', 15, ['tag1', 'tag2']);

        $this->logger->uniqueSet(['metric_2', 'metric_3'], 15, ['tag1', 'tag2']);
//        \Phake::verify($this->client)->set('metric_2', 15, ['tag1', 'tag2']);
//        \Phake::verify($this->client)->set('metric_3', 15, ['tag1', 'tag2']);
    }

    /**
     * @dataProvider dpServiceStates
     *
     * @param int $expected_status
     */
    public function testDogStatsDClientIsCalledCorrectlyWhenSendingServiceCheck(ServiceStatus $status, $expected_status): void
    {
        $this->logger->serviceCheck(
            'service_name',
            $status
        );

//        \Phake::verify($this->client)->serviceCheck(
//            'service_name',
//            $expected_status,
//            [],
//            []
//        );
    }

    public function dpServiceStates(): array
    {
        return [
            [ServiceStatus::OK(), DogStatsd::OK],
            [ServiceStatus::WARNING(), DogStatsd::WARNING],
            [ServiceStatus::CRITICAL(), DogStatsd::CRITICAL],
            [ServiceStatus::UNKNOWN(), DogStatsd::UNKNOWN],
        ];
    }

    public function testDogStatsDClientExceptionIsRethrownAsMonitoringExceptionForIncrement(): void
    {
        static::expectException(MetricsException::class);

//        \Phake::when($this->client)->increment(\Phake::anyParameters())->thenThrow(
//            new \Graze\DogStatsD\Exception\RuntimeException($this->client)
//        );

        $this->logger->increment('metric', 1);
    }

    public function testDogStatsDClientExceptionIsRethrownAsMonitoringExceptionForDecrement(): void
    {
        static::expectException(MetricsException::class);

//        \Phake::when($this->client)->decrement(\Phake::anyParameters())->thenThrow(
//            new \Graze\DogStatsD\Exception\RuntimeException($this->client)
//        );

        $this->logger->decrement('metric', 1);
    }

    public function testDogStatsDClientExceptionIsRethrownAsMonitoringExceptionForTiming(): void
    {
        static::expectException(MetricsException::class);

//        \Phake::when($this->client)->timing(\Phake::anyParameters())->thenThrow(
//            new \Graze\DogStatsD\Exception\RuntimeException($this->client)
//        );

        $this->logger->timing('metric', 1);
    }

    public function testDogStatsDClientExceptionIsRethrownAsMonitoringExceptionForGauge(): void
    {
        static::expectException(MetricsException::class);

//        \Phake::when($this->client)->gauge(\Phake::anyParameters())->thenThrow(
//            new \Graze\DogStatsD\Exception\RuntimeException($this->client)
//        );

        $this->logger->gauge('metric', 1);
    }

    public function testDogStatsDClientExceptionIsRethrownAsMonitoringExceptionForHistogram(): void
    {
        static::expectException(MetricsException::class);

//        \Phake::when($this->client)->histogram(\Phake::anyParameters())->thenThrow(
//            new \Graze\DogStatsD\Exception\RuntimeException($this->client)
//        );

        $this->logger->histogram('metric', 1);
    }

    public function testDogStatsDClientExceptionIsRethrownAsMonitoringExceptionForUniqueSet(): void
    {
        static::expectException(MetricsException::class);

//        \Phake::when($this->client)->set(\Phake::anyParameters())->thenThrow(
//            new \Graze\DogStatsD\Exception\RuntimeException($this->client)
//        );

        $this->logger->uniqueSet('metric', 1);
    }

    public function testEventCorrectlyMapsMetaDataToDogStatsDValues(): void
    {
        $this->logger->event(
            'title',
            'text',
            [
                EventMetadata::TIME              => 12345678,
                EventMetadata::HOSTNAME          => 'hostname',
                EventMetadata::AGGREGATION_GROUP => 'aggregation_key',
                EventMetadata::PRIORITY          => EventPriority::LOW(),
                EventMetadata::SEVERITY          => EventSeverity::SUCCESS(),
            ],
            ['tag1' => 'a']
        );

//        \Phake::verify($this->client)->event(
//            'title',
//            'text',
//            [
//                'time'     => 12345678,
//                'hostname' => 'hostname',
//                'key'      => 'aggregation_key',
//                'priority' => 'low',
//                'alert'    => 'success',
//            ],
//            ['tag1' => 'a']
//        );
    }

    public function testServiceCheckCorrectlyMapsMetaDataToDogStatsDValues(): void
    {
        $this->logger->event(
            'title',
            'text',
            [
                ServiceCheckMetadata::TIME     => 12345678,
                ServiceCheckMetadata::HOSTNAME => 'hostname',
            ],
            ['tag1' => 'a']
        );

//        \Phake::verify($this->client)->event(
//            'title',
//            'text',
//            [
//                'time'     => 12345678,
//                'hostname' => 'hostname',
//            ],
//            ['tag1' => 'a']
//        );
    }
}
