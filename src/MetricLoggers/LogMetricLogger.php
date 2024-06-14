<?php

declare(strict_types=1);

namespace Rapide\Metrics\MetricLoggers;

use Psr\Log\LoggerInterface;
use Rapide\Metrics\Interfaces\EventMetadataSanitizer as EventMetadataSanitizerInterface;
use Rapide\Metrics\Interfaces\MetricLogger;
use Rapide\Metrics\Interfaces\ServiceCheckMetadataSanitizer as ServiceCheckMetadataSanitizerInterface;
use Rapide\Metrics\Interfaces\UsesNamespaces;
use Rapide\Metrics\ServiceStatus;
use Rapide\Metrics\Transformers\EventMetadataSanitizer;
use Rapide\Metrics\Transformers\ServiceCheckMetadataSanitizer;

class LogMetricLogger implements MetricLogger, UsesNamespaces
{
    use AttachTags;
    use NamespaceMetrics;

    private EventMetadataSanitizerInterface $eventMetadataSanitizer;
    private ServiceCheckMetadataSanitizerInterface $serviceCheckMetadataSanitizer;
    private LoggerInterface $logger;

    public static function create(LoggerInterface $logger): self
    {
        return new self(
            new EventMetadataSanitizer(),
            new ServiceCheckMetadataSanitizer(),
            $logger
        );
    }

    public function __construct(
        EventMetadataSanitizerInterface $eventMetadataSanitizer,
        ServiceCheckMetadataSanitizerInterface $serviceCheckMetadataSanitizer,
        LoggerInterface $logger
    ) {
        $this->eventMetadataSanitizer        = $eventMetadataSanitizer;
        $this->serviceCheckMetadataSanitizer = $serviceCheckMetadataSanitizer;
        $this->logger                        = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function increment($metrics, $delta, array $tags = []): void
    {
        $this->logToLogger(__FUNCTION__, $metrics, $delta, $tags);
    }

    /**
     * @param string|string[]  $metrics
     * @param float|int|string $value
     * @param list<string>     $tags
     */
    private function logToLogger(string $functionName, $metrics, $value, array $tags): void
    {
        $metrics = $this->namespaceMetrics($metrics);
        $metrics = implode(', ', is_array($metrics) ? $metrics : [$metrics]);
        $tagLine = implode(', ', $this->getMergedTags($tags));

        $line = "{$functionName}: {$metrics} - {$value} - {$tagLine}" . PHP_EOL;

        $this->logger->debug($line);
    }

    /**
     * {@inheritDoc}
     */
    public function decrement($metrics, $delta, array $tags = []): void
    {
        $this->logToLogger(__FUNCTION__, $metrics, -$delta, $tags);
    }

    /**
     * {@inheritDoc}
     */
    public function timing($metrics, $value, array $tags = []): void
    {
        $this->logToLogger(__FUNCTION__, $metrics, $value, $tags);
    }

    /**
     * {@inheritDoc}
     */
    public function gauge($metrics, $value, array $tags = []): void
    {
        $this->logToLogger(__FUNCTION__, $metrics, $value, $tags);
    }

    /**
     * {@inheritDoc}
     */
    public function histogram($metrics, $value, array $tags = []): void
    {
        $this->logToLogger(__FUNCTION__, $metrics, $value, $tags);
    }

    /**
     * {@inheritDoc}
     */
    public function distribution($metrics, $value, array $tags = []): void
    {
        $this->logToLogger(__FUNCTION__, $metrics, $value, $tags);
    }

    /**
     * {@inheritDoc}
     */
    public function uniqueSet($metrics, $value, array $tags = []): void
    {
        $this->logToLogger(__FUNCTION__, $metrics, $value, $tags);
    }

    /**
     * {@inheritDoc}
     */
    public function event($title, $text, array $metadata = [], array $tags = []): void
    {
        $metadata = $this->eventMetadataSanitizer->sanitize($metadata);

        $this->logToFileWithMetadata(__FUNCTION__, $title, $text, $metadata, $tags);
    }

    /**
     * @param string|string[]  $metrics
     * @param float|int|string $value
     */
    private function logToFileWithMetadata(string $functionName, $metrics, $value, array $metadata, array $tags): void
    {
        $this->logToLogger(
            $functionName,
            $metrics,
            $value . ' | ' . print_r($metadata, true),
            $tags
        );
    }

    /**
     * {@inheritDoc}
     */
    public function serviceCheck($name, ServiceStatus $status, array $metadata = [], array $tags = []): void
    {
        $metadata = $this->serviceCheckMetadataSanitizer->sanitize($metadata);

        $this->logToFileWithMetadata(__FUNCTION__, $name, $status->getKey(), $metadata, $tags);
    }
}
