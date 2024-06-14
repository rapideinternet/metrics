<?php

declare(strict_types=1);

namespace Rapide\Metrics\MetricLoggers;

use Rapide\Metrics\Interfaces\EventMetadataSanitizer as EventMetadataSanitizerInterface;
use Rapide\Metrics\Interfaces\MetricLogger;
use Rapide\Metrics\Interfaces\ServiceCheckMetadataSanitizer as ServiceCheckMetadataSanitizerInterface;
use Rapide\Metrics\ServiceStatus;
use Rapide\Metrics\Transformers\EventMetadataSanitizer;
use Rapide\Metrics\Transformers\ServiceCheckMetadataSanitizer;

class NullMetricLogger implements MetricLogger
{
    use AttachTags;

    private EventMetadataSanitizerInterface $eventMetadataSanitizer;
    private ServiceCheckMetadataSanitizerInterface $serviceCheckMetadataSanitizer;

    public static function create(): self
    {
        return new self(
            new EventMetadataSanitizer(),
            new ServiceCheckMetadataSanitizer()
        );
    }

    public function __construct(
        EventMetadataSanitizerInterface $event_metadata_sanitizer,
        ServiceCheckMetadataSanitizerInterface $service_check_metadata_sanitizer
    ) {
        $this->eventMetadataSanitizer        = $event_metadata_sanitizer;
        $this->serviceCheckMetadataSanitizer = $service_check_metadata_sanitizer;
    }

    /**
     * {@inheritDoc}
     */
    public function increment($metrics, $delta, array $tags = []): void
    {
    }

    /**
     * {@inheritDoc}
     */
    public function decrement($metrics, $delta, array $tags = []): void
    {
    }

    /**
     * {@inheritDoc}
     */
    public function timing($metrics, $value, array $tags = []): void
    {
    }

    /**
     * {@inheritDoc}
     */
    public function gauge($metrics, $value, array $tags = []): void
    {
    }

    /**
     * {@inheritDoc}
     */
    public function histogram($metrics, $value, array $tags = []): void
    {
    }

    /**
     * {@inheritDoc}
     */
    public function distribution($metrics, $value, array $tags = []): void
    {
    }

    /**
     * {@inheritDoc}
     */
    public function uniqueSet($metrics, $value, array $tags = []): void
    {
    }

    /**
     * {@inheritDoc}
     */
    public function event($title, $text, array $metadata = [], array $tags = []): void
    {
        /*
         * Even tough we don't use the metadata, we must throw exceptions when it contains invalid data to adhere to
         * the interface specification.
         */
        $this->eventMetadataSanitizer->sanitize($metadata);
    }

    /**
     * {@inheritDoc}
     */
    public function serviceCheck($name, ServiceStatus $status, array $metadata = [], array $tags = []): void
    {
        /*
         * Even tough we don't use the metadata, we must throw exceptions when it contains invalid data to adhere to
         * the interface specification.
         */
        $this->serviceCheckMetadataSanitizer->sanitize($metadata);
    }
}
