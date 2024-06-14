<?php

declare(strict_types=1);

namespace Rapide\Metrics\MetricLoggers\Exceptions;

class MetricLoggingFailureException extends AbstractException
{
    public static function dogStatsD(\Exception $e): self
    {
        return new self($e->getMessage(), $e->getCode(), $e);
    }

    public static function unknownServiceStatusCode(int $serviceStatusCode): self
    {
        return new self("Unknown status code '{$serviceStatusCode}', cannot report service check");
    }

    /**
     * @param float|int|string $metadataValue
     */
    public static function invalidEventMetadataValue(string $metadataKey, $metadataValue): self
    {
        return new self("Invalid metadata value '{$metadataValue}' for key '{$metadataKey}'");
    }
}
