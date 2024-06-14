<?php

declare(strict_types=1);

namespace Rapide\Metrics;

/**
 * This class details the keys that are allowed for service check metadata.
 *
 * @see \Rapide\Metrics\Interfaces\MetricLogger::serviceCheck()
 */
final class ServiceCheckMetadata
{
    /** Assign a timestamp to the service check to prevent the current timestamp from being used. */
    public const TIME = 'time';

    /** Assign a hostname to the service check, for example to differentiate between servers the event might come from. */
    public const HOSTNAME = 'hostname';

    private function __construct()
    {
    }
}
