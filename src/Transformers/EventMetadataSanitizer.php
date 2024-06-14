<?php

declare(strict_types=1);

namespace Rapide\Metrics\Transformers;

use Rapide\Metrics\EventMetadata;
use Rapide\Metrics\EventPriority;
use Rapide\Metrics\EventSeverity;
use Rapide\Metrics\Interfaces\EventMetadataSanitizer as EventMetadataSanitizerInterface;
use Rapide\Metrics\MetricLoggers\Exceptions\MetricLoggingFailureException;

final class EventMetadataSanitizer implements EventMetadataSanitizerInterface
{
    /**
     * @throws MetricLoggingFailureException
     */
    public function sanitize(array $metadata): array
    {
        $sanitizedMetadata = [];

        if (array_key_exists(EventMetadata::TIME, $metadata)) {
            $sanitizedMetadata[EventMetadata::TIME] = EventMetadataSanitizer::sanitizeTime($metadata);
        }

        if (array_key_exists(EventMetadata::SEVERITY, $metadata)) {
            $sanitizedMetadata[EventMetadata::SEVERITY] = EventMetadataSanitizer::sanitizeSeverity($metadata);
        }

        if (array_key_exists(EventMetadata::AGGREGATION_GROUP, $metadata)) {
            $sanitizedMetadata[EventMetadata::AGGREGATION_GROUP] = EventMetadataSanitizer::sanitizeAggregationGroup($metadata);
        }

        if (array_key_exists(EventMetadata::HOSTNAME, $metadata)) {
            $sanitizedMetadata[EventMetadata::HOSTNAME] = EventMetadataSanitizer::sanitizeHostname($metadata);
        }

        if (array_key_exists(EventMetadata::PRIORITY, $metadata)) {
            $sanitizedMetadata[EventMetadata::PRIORITY] = EventMetadataSanitizer::sanitizePriority($metadata);
        }

        return $sanitizedMetadata;
    }

    /**
     * @throws MetricLoggingFailureException
     */
    private static function sanitizeTime(array $metadata): int
    {
        $timestamp = $metadata[EventMetadata::TIME];

        if (is_int($timestamp)) {
            return $timestamp;
        }

        throw MetricLoggingFailureException::invalidEventMetadataValue(EventMetadata::TIME, $timestamp);
    }

    /**
     * @throws MetricLoggingFailureException
     */
    private static function sanitizeSeverity(array $metadata): EventSeverity
    {
        $severity = $metadata[EventMetadata::SEVERITY];

        if ($severity instanceof EventSeverity) {
            return $severity;
        }

        throw MetricLoggingFailureException::invalidEventMetadataValue(EventMetadata::SEVERITY, $severity);
    }

    /**
     * @throws MetricLoggingFailureException
     */
    private static function sanitizeAggregationGroup(array $metadata): string
    {
        $aggregation_group = $metadata[EventMetadata::AGGREGATION_GROUP];

        if (is_string($aggregation_group)) {
            return $aggregation_group;
        }

        throw MetricLoggingFailureException::invalidEventMetadataValue(EventMetadata::AGGREGATION_GROUP, $aggregation_group);
    }

    /**
     * @throws MetricLoggingFailureException
     */
    private static function sanitizeHostname(array $metadata): string
    {
        $hostname = $metadata[EventMetadata::HOSTNAME];

        if (is_string($hostname)) {
            return $hostname;
        }

        throw MetricLoggingFailureException::invalidEventMetadataValue(EventMetadata::HOSTNAME, $hostname);
    }

    /**
     * @throws MetricLoggingFailureException
     */
    private static function sanitizePriority(array $metadata): EventPriority
    {
        $priority = $metadata[EventMetadata::PRIORITY];

        if ($priority instanceof EventPriority) {
            return $priority;
        }

        throw MetricLoggingFailureException::invalidEventMetadataValue(EventMetadata::PRIORITY, $priority);
    }
}
