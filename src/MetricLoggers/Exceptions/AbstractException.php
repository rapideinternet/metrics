<?php

declare(strict_types=1);

namespace Rapide\Metrics\MetricLoggers\Exceptions;

use Exception;
use Rapide\Metrics\Exceptions\MetricsException;

class AbstractException extends Exception implements MetricsException
{
}
