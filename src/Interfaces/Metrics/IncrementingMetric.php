<?php

declare(strict_types=1);

namespace Rapide\Metrics\Interfaces\Metrics;

interface IncrementingMetric
{
    public function metricPath(): string;

    public function delta(): int;

    public function tags(): array;
}
