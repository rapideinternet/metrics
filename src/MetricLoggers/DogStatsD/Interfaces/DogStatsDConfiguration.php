<?php

declare(strict_types=1);

namespace Rapide\Metrics\MetricLoggers\DogStatsD\Interfaces;

interface DogStatsDConfiguration
{
    public function getHost(): string;

    public function getPort(): int;

    public function getDataDogHost(): string;

    public function getTimeout(): float;

    public function getNamespace(): string;
}
