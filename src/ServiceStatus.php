<?php

declare(strict_types=1);

namespace Rapide\Metrics;

use MyCLabs\Enum\Enum;

/**
 * @method static ServiceStatus OK();
 * @method static ServiceStatus WARNING();
 * @method static ServiceStatus CRITICAL();
 * @method static ServiceStatus UNKNOWN();
 *
 * @extends Enum<string>
 */
final class ServiceStatus extends Enum
{
    public const OK       = 'ok';
    public const WARNING  = 'warning';
    public const CRITICAL = 'critical';
    public const UNKNOWN  = 'unknown';
}
