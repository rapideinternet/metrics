<?php

declare(strict_types=1);

namespace Rapide\Metrics\Exceptions;

use RuntimeException;

class CounterException extends RuntimeException implements MetricsException
{
}
