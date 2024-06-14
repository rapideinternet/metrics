<?php

declare(strict_types=1);

namespace Rapide\Metrics\MetricLoggers;

use Rapide\Metrics\Interfaces\EventMetadataSanitizer as EventMetadataSanitizerInterface;
use Rapide\Metrics\Interfaces\MetricLogger;
use Rapide\Metrics\Interfaces\ServiceCheckMetadataSanitizer as ServiceCheckMetadataSanitizerInterface;
use Rapide\Metrics\Interfaces\UsesNamespaces;
use Rapide\Metrics\ServiceStatus;
use Rapide\Metrics\Transformers\EventMetadataSanitizer;
use Rapide\Metrics\Transformers\ServiceCheckMetadataSanitizer;

/**
 * Use this class when you want to log the metrics to a file, which can be useful for development/testing purposes.
 */
class FileMetricLogger implements MetricLogger, UsesNamespaces
{
    use AttachTags;
    use NamespaceMetrics;

    private string $logFile;

    private EventMetadataSanitizerInterface $eventMetadataSanitizer;

    private ServiceCheckMetadataSanitizerInterface $serviceCheckMetadataSanitizer;

    public static function create(string $logFile): self
    {
        return new self(
            new EventMetadataSanitizer(),
            new ServiceCheckMetadataSanitizer(),
            $logFile
        );
    }

    public function __construct(
        EventMetadataSanitizerInterface $eventMetadataSanitizer,
        ServiceCheckMetadataSanitizerInterface $serviceCheckMetadataSanitizer,
        string $logFile
    ) {
        $this->eventMetadataSanitizer        = $eventMetadataSanitizer;
        $this->serviceCheckMetadataSanitizer = $serviceCheckMetadataSanitizer;
        $this->logFile                       = $logFile;
    }

    /**
     * {@inheritDoc}
     */
    public function increment($metrics, $delta, array $tags = []): void
    {
        $this->logToFile(__FUNCTION__, $metrics, $delta, $tags);
    }

    /**
     * @param string|string[]  $metrics
     * @param float|int|string $value
     * @param list<string>     $tags
     */
    private function logToFile(string $functionName, $metrics, $value, array $tags): void
    {
        $metrics = $this->namespaceMetrics($metrics);
        $metrics = implode(', ', is_array($metrics) ? $metrics : [$metrics]);
        $tagLine = implode(', ', $this->getMergedTags($tags));

        $line = "{$functionName}: {$metrics} - {$value} - {$tagLine}" . PHP_EOL;

        file_put_contents($this->logFile, $line, FILE_APPEND);
    }

    /**
     * {@inheritDoc}
     */
    public function decrement($metrics, $delta, array $tags = []): void
    {
        $this->logToFile(__FUNCTION__, $metrics, -$delta, $tags);
    }

    /**
     * {@inheritDoc}
     */
    public function timing($metrics, $value, array $tags = []): void
    {
        $this->logToFile(__FUNCTION__, $metrics, $value, $tags);
    }

    /**
     * {@inheritDoc}
     */
    public function gauge($metrics, $value, array $tags = []): void
    {
        $this->logToFile(__FUNCTION__, $metrics, $value, $tags);
    }

    /**
     * {@inheritDoc}
     */
    public function histogram($metrics, $value, array $tags = []): void
    {
        $this->logToFile(__FUNCTION__, $metrics, $value, $tags);
    }

    /**
     * {@inheritDoc}
     */
    public function distribution($metrics, $value, array $tags = []): void
    {
        $this->logToFile(__FUNCTION__, $metrics, $value, $tags);
    }

    /**
     * {@inheritDoc}
     */
    public function uniqueSet($metrics, $value, array $tags = []): void
    {
        $this->logToFile(__FUNCTION__, $metrics, $value, $tags);
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
        $this->logToFile(
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
