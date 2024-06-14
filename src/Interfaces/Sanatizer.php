<?php

declare(strict_types=1);

namespace Rapide\Metrics\Interfaces;

use Rapide\Metrics\MetricLoggers\Exceptions\MetricLoggingFailureException;

interface Sanatizer
{
    /**
     * @throws MetricLoggingFailureException
     */
    public function sanitize(array $metadata): array;
}
