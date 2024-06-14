<?php

declare(strict_types=1);

namespace Rapide\Metrics\Transformers;

use Rapide\Metrics\Interfaces\ServiceCheckMetadataSanitizer as ServiceCheckMetadataSanitizerInterface;
use Rapide\Metrics\MetricLoggers\Exceptions\MetricLoggingFailureException;
use Rapide\Metrics\ServiceCheckMetadata;

final class ServiceCheckMetadataSanitizer implements ServiceCheckMetadataSanitizerInterface
{
    /**
     * @throws MetricLoggingFailureException
     */
    public function sanitize(array $metadata): array
    {
        $sanitizedMetadata = [];

        if (array_key_exists(ServiceCheckMetadata::TIME, $metadata)) {
            $sanitizedMetadata[ServiceCheckMetadata::TIME] = ServiceCheckMetadataSanitizer::sanitizeTime($metadata);
        }

        if (array_key_exists(ServiceCheckMetadata::HOSTNAME, $metadata)) {
            $sanitizedMetadata[ServiceCheckMetadata::HOSTNAME] = ServiceCheckMetadataSanitizer::sanitizeHostname($metadata);
        }

        return $sanitizedMetadata;
    }

    /**
     * @throws MetricLoggingFailureException
     */
    private static function sanitizeTime(array $metadata): int
    {
        $timestamp = $metadata[ServiceCheckMetadata::TIME];

        if (is_int($timestamp)) {
            return $timestamp;
        }

        throw MetricLoggingFailureException::invalidEventMetadataValue(ServiceCheckMetadata::TIME, $timestamp);
    }

    /**
     * @throws MetricLoggingFailureException
     */
    private static function sanitizeHostname(array $metadata): string
    {
        $hostname = $metadata[ServiceCheckMetadata::HOSTNAME];

        if (is_string($hostname)) {
            return $hostname;
        }

        throw MetricLoggingFailureException::invalidEventMetadataValue(ServiceCheckMetadata::HOSTNAME, $hostname);
    }
}
