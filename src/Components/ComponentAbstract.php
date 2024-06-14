<?php

declare(strict_types=1);

namespace Rapide\Metrics\Components;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Rapide\Metrics\Interfaces\ClassShortener;
use Rapide\Metrics\Interfaces\Collector;
use Rapide\Metrics\Interfaces\ComponentInterface;

abstract class ComponentAbstract implements ComponentInterface
{
    protected Application $app;

    protected ClassShortener $classShortener;

    protected Dispatcher $dispatcher;

    protected Collector $collector;

    public function __construct(
        Application $application,
        ClassShortener $classShortener,
        Collector $collector,
        Dispatcher $dispatcher
    ) {
        $this->app            = $application;
        $this->classShortener = $classShortener;
        $this->collector      = $collector;
        $this->dispatcher     = $dispatcher;
    }

    abstract public function register(): void;

    /**
     * @param class-string $className
     */
    protected function getShortName(string $className): string
    {
        return $this->classShortener->shorten($className);
    }

    protected function listen(string $event, \Closure $callback): void
    {
        $this->dispatcher->listen($event, $callback);
    }
}
