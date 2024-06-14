<?php

declare(strict_types=1);

namespace Rapide\Metrics\MetricLoggers\DogStatsD;

use Rapide\Metrics\EventMetadata;
use Rapide\Metrics\EventPriority;
use Rapide\Metrics\EventSeverity;
use Rapide\Metrics\Interfaces\EventMetadataSanitizer as EventMetadataSanitizerInterface;
use Rapide\Metrics\MetricLoggers\Exceptions\MetricLoggingFailureException;

class EventMetadataTransformer implements EventMetadataSanitizerInterface
{
    private EventMetadataSanitizerInterface $eventMetadataSanitizer;

    public function __construct(EventMetadataSanitizerInterface $eventMetadataSanitizer)
    {
        $this->eventMetadataSanitizer = $eventMetadataSanitizer;
    }

    /**
     * @throws MetricLoggingFailureException
     */
    public function sanitize(array $metadata): array
    {
        $metadata          = $this->eventMetadataSanitizer->sanitize($metadata);
        $dogStatsDMetadata = [];

        if (array_key_exists(EventMetadata::TIME, $metadata)) {
            $dogStatsDMetadata['time'] = $metadata[EventMetadata::TIME];
        }

        if (array_key_exists(EventMetadata::SEVERITY, $metadata)) {
            $dogStatsDMetadata['alert'] = EventMetadataTransformer::severityToDogStatsDAlert($metadata[EventMetadata::SEVERITY]);
        }

        if (array_key_exists(EventMetadata::AGGREGATION_GROUP, $metadata)) {
            $dogStatsDMetadata['key'] = $metadata[EventMetadata::AGGREGATION_GROUP];
        }

        if (array_key_exists(EventMetadata::HOSTNAME, $metadata)) {
            $dogStatsDMetadata['hostname'] = $metadata[EventMetadata::HOSTNAME];
        }

        if (array_key_exists(EventMetadata::PRIORITY, $metadata)) {
            $dogStatsDMetadata['priority'] = EventMetadataTransformer::priorityToDogStatsDPriority($metadata[EventMetadata::PRIORITY]);
        }

        return $dogStatsDMetadata;
    }

    private static function severityToDogStatsDAlert(EventSeverity $severity): string
    {
        /*
         * The DogStatsD values for the 'alert' field match the constant values on the enum
         */
        return $severity->getValue();
    }

    private function priorityToDogStatsDPriority(EventPriority $priority): string
    {
        /*
         * The DogStatsD values for the 'priority' field match the constant values on the enum
         */
        return strtolower($priority->getValue());
    }
}
