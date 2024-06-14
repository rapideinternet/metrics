<?php

namespace Rapide\Metrics\Interfaces;

interface UsesNamespaces
{
    public function setNamespace(string $namespace): void;

    /**
     * @param array<string>|string $metrics
     *
     * @return array<string>|string
     */
    public function namespaceMetrics($metrics);

    public function getTitle(string $title): string;
}
