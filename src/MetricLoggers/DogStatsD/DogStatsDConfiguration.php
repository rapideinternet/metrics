<?php

declare(strict_types=1);

namespace Rapide\Metrics\MetricLoggers\DogStatsD;

final class DogStatsDConfiguration implements \Rapide\Metrics\MetricLoggers\DogStatsD\Interfaces\DogStatsDConfiguration
{
    private string $host;
    private int $port;
    private float $timeout;
    private string $datadogHost;

    public function __construct(array $config)
    {
        $this->host        = $config['host'];
        $this->port        = $config['port']         ?? 8125;
        $this->timeout     = $config['timeout']      ?? 5.0;
        $this->datadogHost = $config['datadog_host'] ?? null;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getTimeout(): float
    {
        return $this->timeout;
    }

    public function getDatadogHost(): string
    {
        return $this->datadogHost;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }
}
