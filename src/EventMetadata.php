<?php

declare(strict_types=1);

namespace Rapide\Metrics;

/**
 * This class details the keys that are allowed for event metadata.
 *
 * @see \Rapide\Metrics\Interfaces\MetricLogger::event()
 */
final class EventMetadata
{
    /** Assign a timestamp to the event to prevent the current timestamp from being used. */
    public const TIME = 'time';

    /** Assign a hostname to the event, for example to differentiate between servers the event might come from. */
    public const HOSTNAME = 'hostname';

    /** Assign an aggregation key to the event, allowing it to be grouped with other events. */
    public const AGGREGATION_GROUP = 'aggregation_group';

    /** @see EventPriority for possible values */
    public const PRIORITY = 'priority';

    /** @see EventSeverity for possible values */
    public const SEVERITY = 'severity';

    private function __construct()
    {
    }
}
