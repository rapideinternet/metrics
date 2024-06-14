<?php

declare(strict_types=1);

namespace Rapide\Metrics;

use MyCLabs\Enum\Enum;

/**
 * @method static EventSeverity SUCCESS();
 * @method static EventSeverity INFO();
 * @method static EventSeverity WARNING();
 * @method static EventSeverity ERROR();
 *
 * @extends Enum<string>
 */
final class EventSeverity extends Enum
{
    public const SUCCESS = 'success';
    public const INFO    = 'info';
    public const WARNING = 'warning';
    public const ERROR   = 'error';
}
