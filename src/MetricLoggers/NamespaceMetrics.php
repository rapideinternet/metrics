<?php

declare(strict_types=1);

namespace Rapide\Metrics\MetricLoggers;

trait NamespaceMetrics
{
    protected string $namespace;

    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * @param array<string>|string $metrics
     *
     * @return array<string>|string
     */
    public function namespaceMetrics($metrics)
    {
        if (empty($this->namespace)) {
            return $metrics;
        }

        if (is_array($metrics)) {
            return array_map('getTitle', $metrics);
        }

        return $this->getTitle($metrics);
    }

    public function getTitle(string $title): string
    {
        return $this->namespace . '.' . $title;
    }
}
