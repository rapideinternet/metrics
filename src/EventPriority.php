<?php

declare(strict_types=1);

namespace Rapide\Metrics;

use MyCLabs\Enum\Enum;

/**
 * @method static EventPriority LOW();
 * @method static EventPriority HIGH();
 *
 * @extends Enum<string>
 */
final class EventPriority extends Enum
{
    public const LOW  = 'low';
    public const HIGH = 'high';
}
