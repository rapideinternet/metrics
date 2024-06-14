<?php

declare(strict_types=1);

namespace Rapide\Metrics\MetricLoggers\DogStatsD;

use DataDog\DogStatsd;
use Exception;
use Rapide\Metrics\Interfaces\MetricLogger;
use Rapide\Metrics\Interfaces\UsesNamespaces;
use Rapide\Metrics\MetricLoggers\AttachTags;
use Rapide\Metrics\MetricLoggers\DogStatsD\Interfaces\DogStatsDConfiguration;
use Rapide\Metrics\MetricLoggers\Exceptions\MetricLoggingFailureException;
use Rapide\Metrics\MetricLoggers\NamespaceMetrics;
use Rapide\Metrics\ServiceStatus;
use Rapide\Metrics\Transformers\EventMetadataSanitizer;
use Rapide\Metrics\Transformers\ServiceCheckMetadataSanitizer;
use function array_pop;

class DogStatsDMetricLogger implements MetricLogger, UsesNamespaces
{
    use AttachTags;
    use NamespaceMetrics;

    private DogStatsDConfiguration $configuration;
    private DogStatsd $client;
    private EventMetadataTransformer $eventMetadataTransformer;
    private ServiceCheckMetadataTransformer $serviceCheckMetadataTransformer;

    public static function create(DogStatsDConfiguration $configuration): self
    {
        return new self(
            $configuration,
            new DogStatsd([
                'host'         => $configuration->getHost(),
                'port'         => $configuration->getPort(),
                'datadog_host' => $configuration->getDataDogHost(),
                'timeout'      => $configuration->getTimeout(),
            ]),
            new EventMetadataTransformer(new EventMetadataSanitizer()),
            new ServiceCheckMetadataTransformer(new ServiceCheckMetadataSanitizer())
        );
    }

    public function __construct(
        DogStatsDConfiguration $configuration,
        DogStatsd $client,
        EventMetadataTransformer $eventMetadataTransformer,
        ServiceCheckMetadataTransformer $serviceCheckMetadataTransformer
    ) {
        $this->configuration                   = $configuration;
        $this->client                          = $client;
        $this->eventMetadataTransformer        = $eventMetadataTransformer;
        $this->serviceCheckMetadataTransformer = $serviceCheckMetadataTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function increment($metrics, $delta, array $tags = []): void
    {
        $metrics = is_array($metrics) ? $metrics : [$metrics];
        $tags    = $this->getMergedTags($tags);

        foreach ($metrics as $metric) {
            try {
                $this->client->increment($this->namespaceMetrics($metric), 1.0, $tags, $delta);
            } catch (Exception $e) {
                throw MetricLoggingFailureException::dogStatsD($e);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function decrement($metrics, $delta, array $tags = []): void
    {
        $metrics = is_array($metrics) ? $metrics : [$metrics];
        $tags    = $this->getMergedTags($tags);

        foreach ($metrics as $metric) {
            try {
                $this->client->decrement($this->namespaceMetrics($metric), 1.0, $tags, $delta);
            } catch (Exception $e) {
                throw MetricLoggingFailureException::dogStatsD($e);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function timing($metrics, $value, array $tags = []): void
    {
        $this->callClientForEachMetric('timing', $metrics, $value, 1.0, $tags);
    }

    /**
     * @param string|string[] $metrics
     * @param array           ...$args
     *
     * @throws MetricLoggingFailureException
     */
    private function callClientForEachMetric(string $functionName, $metrics, ...$args): void
    {
        $metrics = is_array($metrics) ? $metrics : [$metrics];

        // tags is always the last argument
        $tags = array_pop($args) ?? [];

        // Replace it with merged
        $args[] = $this->getMergedTags($tags);

        foreach ($metrics as $metric) {
            try {
                $this->client->{$functionName}($this->namespaceMetrics($metric), ...$args);
            } catch (Exception $e) {
                throw MetricLoggingFailureException::dogStatsD($e);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function gauge($metrics, $value, array $tags = []): void
    {
        $this->callClientForEachMetric('gauge', $metrics, $value, 1.0, $tags);
    }

    /**
     * {@inheritdoc}
     */
    public function histogram($metrics, $value, array $tags = []): void
    {
        $this->callClientForEachMetric('histogram', $metrics, $value, 1.0, $tags);
    }

    /**
     * {@inheritdoc}
     */
    public function distribution($metrics, $value, array $tags = []): void
    {
        $this->callClientForEachMetric('distribution', $metrics, $value, 1.0, $tags);
    }

    /**
     * {@inheritdoc}
     */
    public function uniqueSet($metrics, $value, array $tags = []): void
    {
        $this->callClientForEachMetric('set', $metrics, $value, 1.0, $tags);
    }

    /**
     * {@inheritdoc}
     */
    public function event($title, $text, array $metadata = [], array $tags = []): void
    {
        $metadata = $this->eventMetadataTransformer->sanitize($metadata);

        $data = array_merge([
            'tags' => $tags,
        ], $metadata);

        $this->client->event($this->getTitle($title), $data);
    }

    /**
     * {@inheritdoc}
     */
    public function serviceCheck($name, ServiceStatus $status, array $metadata = [], array $tags = []): void
    {
        $metadata = $this->serviceCheckMetadataTransformer->sanitize($metadata);
        $name     = $this->namespaceMetrics($name);

        $this->client->serviceCheck(
            $name,
            $this->serviceStatusToDogSTatsDStatus($status),
            $tags,
            $metadata['hostname'] ?? null,
            null,
            $metadata['time'] ?? null
        );
    }

    private function serviceStatusToDogSTatsDStatus(ServiceStatus $status): int
    {
        switch ($status->getValue()) {
            case ServiceStatus::OK:
                return DogStatsd::OK;

            case ServiceStatus::WARNING:
                return DogStatsd::WARNING;

            case ServiceStatus::CRITICAL:
                return DogStatsd::CRITICAL;

            case ServiceStatus::UNKNOWN:
            default:
                return DogStatsd::UNKNOWN;
        }
    }
}
