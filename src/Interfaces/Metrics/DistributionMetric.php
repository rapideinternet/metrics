<?php

declare(strict_types=1);

namespace Rapide\Metrics\Interfaces\Metrics;

interface DistributionMetric
{
    public function metricPath(): string;

    public function value(): float;

    public function tags(): array;
}
