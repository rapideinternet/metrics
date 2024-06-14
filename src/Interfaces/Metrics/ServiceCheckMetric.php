<?php

declare(strict_types=1);

namespace Rapide\Metrics\Interfaces\Metrics;

use Rapide\Metrics\ServiceStatus;

interface ServiceCheckMetric
{
    public function metricPath(): string;

    public function status(): ServiceStatus;

    public function metadata(): array;

    public function tags(): array;
}
