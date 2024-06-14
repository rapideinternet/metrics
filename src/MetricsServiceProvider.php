<?php

declare(strict_types=1);

namespace Rapide\Metrics;

use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;
use Rapide\Metrics\Exceptions\InvalidSettingsException;
use Rapide\Metrics\Interfaces\Collector;
use Rapide\Metrics\Interfaces\MetricLogger;
use Rapide\Metrics\Interfaces\UsesNamespaces;
use Rapide\Metrics\MetricLoggers\CompositeMetricLogger;
use Rapide\Metrics\MetricLoggers\DogStatsD\DogStatsDConfiguration;
use Rapide\Metrics\MetricLoggers\DogStatsD\DogStatsDMetricLogger;
use Rapide\Metrics\MetricLoggers\FileMetricLogger;
use Rapide\Metrics\MetricLoggers\LogMetricLogger;
use Rapide\Metrics\MetricLoggers\NullMetricLogger;

final class MetricsServiceProvider extends ServiceProvider
{
    private array $config;

    public function boot(): void
    {
        $this->publishes([$this->configPath() => config_path('metrics.php')]);
    }

    protected function configPath(): string
    {
        return __DIR__ . '/../config/metrics.php';
    }

    public function register(): void
    {
        $this->mergeConfigFrom($this->configPath(), 'metrics');
        $this->config = $this->app->get('config')->get('metrics');

        $this->app->bind(Interfaces\ClassShortener::class, Services\ClassShortener::class);
        $this->app->bind(Collector::class, MetricCollector::class);

        $this->registerMetricLogger();
    }

    private function registerMetricLogger(): void
    {
        $this->app->bind(MetricLogger::class, function () {
            $logger = $this->config['enabled'] ? $this->config['logger'] : 'null';

            if (is_array($logger)) {
                return $this->createCompositeLogger($logger);
            }

            return $this->createLogger($logger);
        });
    }

    private function createCompositeLogger(array $loggers): CompositeMetricLogger
    {
        $compositeLogger = new CompositeMetricLogger();

        foreach ($loggers as $logger) {
            $compositeLogger->addMetricLogger($this->createLogger($logger));
        }

        return $compositeLogger;
    }

    private function createLogger(?string $type = null): MetricLogger
    {
        switch ($type) {
            case 'null':
                $metricsLogger = NullMetricLogger::create();

            break;

            case 'datadog':
                $metricsLogger = DogStatsDMetricLogger::create(new DogStatsDConfiguration($this->config['datadog']));

                break;

            case 'file':
                $metricsLogger = FileMetricLogger::create($this->config['file']['file']);

                break;

            case 'log':
                $metricsLogger = LogMetricLogger::create($this->app->get(LoggerInterface::class));

                break;

            default:
                throw new InvalidSettingsException(sprintf('Invalid logger %s', $type));
        }

        if ($metricsLogger instanceof UsesNamespaces) {
            $metricsLogger->setNamespace($this->config['namespace']);
        }

        return $metricsLogger;
    }
}
