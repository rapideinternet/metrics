<?php

declare(strict_types=1);

namespace Rapide\Metrics\MetricLoggers;

use Rapide\Metrics\Interfaces\MetricLogger;
use Rapide\Metrics\Interfaces\UsesNamespaces;
use Rapide\Metrics\ServiceStatus;

final class CompositeMetricLogger implements MetricLogger
{
    use NamespaceMetrics;

    /** @var array<class-string, MetricLogger> */
    private array $metricLoggers = [];

    /** @var array<string, string> */
    private array $tags = [];

    public function addMetricLogger(MetricLogger $metricLogger): void
    {
        foreach ($this->tags as $tag => $value) {
            $metricLogger->attachTag($tag, $value);
        }

        $this->metricLoggers[get_class($metricLogger)] = $metricLogger;
    }

    /**
     * {@inheritDoc}
     */
    public function increment($metrics, $delta, array $tags = []): void
    {
        foreach ($this->metricLoggers as $metricLogger) {
            $metricLogger->increment($metrics, $delta, $tags);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function decrement($metrics, $delta, array $tags = []): void
    {
        foreach ($this->metricLoggers as $metricLogger) {
            $metricLogger->decrement($metrics, $delta, $tags);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function timing($metrics, $value, array $tags = []): void
    {
        foreach ($this->metricLoggers as $metricLogger) {
            $metricLogger->timing($metrics, $value, $tags);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function gauge($metrics, $value, array $tags = []): void
    {
        foreach ($this->metricLoggers as $metricLogger) {
            $metricLogger->gauge($metrics, $value, $tags);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function histogram($metrics, $value, array $tags = []): void
    {
        foreach ($this->metricLoggers as $metricLogger) {
            $metricLogger->histogram($metrics, $value, $tags);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function distribution($metrics, $value, array $tags = []): void
    {
        foreach ($this->metricLoggers as $metricLogger) {
            $metricLogger->distribution($metrics, $value, $tags);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function uniqueSet($metrics, $value, array $tags = []): void
    {
        foreach ($this->metricLoggers as $metricLogger) {
            $metricLogger->uniqueSet($metrics, $value, $tags);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function event($title, $text, array $metadata = [], array $tags = []): void
    {
        foreach ($this->metricLoggers as $metricLogger) {
            $metricLogger->event($title, $text, $metadata, $tags);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function serviceCheck($name, ServiceStatus $status, array $metadata = [], array $tags = []): void
    {
        foreach ($this->metricLoggers as $metricLogger) {
            $metricLogger->serviceCheck($name, $status, $metadata, $tags);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function attachTag(string $tag, string $value): void
    {
        $this->tags[$tag] = $value;

        foreach ($this->metricLoggers as $metricLogger) {
            $metricLogger->attachTag($tag, $value);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getAttachedTags(): array
    {
        return $this->tags;
    }

    /**
     * {@inheritDoc}
     */
    public function resetAttachedTags(): void
    {
        $this->tags = [];

        foreach ($this->metricLoggers as $metricLogger) {
            $metricLogger->resetAttachedTags();
        }
    }

    public function setNamespace(string $namespace): void
    {
        foreach ($this->metricLoggers as $metricLogger) {
            if ($metricLogger instanceof UsesNamespaces) {
                $metricLogger->setNamespace($this->namespace);
            }
        }
    }
}
