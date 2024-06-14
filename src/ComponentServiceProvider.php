<?php

declare(strict_types=1);

namespace Rapide\Metrics;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Rapide\Metrics\Interfaces\ComponentInterface;
use Rapide\Metrics\Services\CounterManager;
use Rapide\Metrics\Services\TimerManager;
use RuntimeException;

final class ComponentServiceProvider extends ServiceProvider
{
    /**
     * @throws BindingResolutionException
     */
    public function register(): void
    {
        if ($this->app->get('config')->get('metrics.enabled')) {
            $this->app->singleton(TimerManager::class);
            $this->app->singleton(CounterManager::class);

            /** @var list<class-string> $components */
            $components = $this->app->get('config')->get('metrics.components') ?? [];

            $this->registerComponents($components);
        }
    }

    /**
     * @param list<class-string> $components
     *
     * @throws BindingResolutionException
     */
    private function registerComponents(array $components): void
    {
        foreach ($components as $componentClass) {
            $component = $this->app->make($componentClass);

            if (!$component instanceof ComponentInterface) {
                throw new RuntimeException('Component must have ComponentInterface');
            }

            $component->register();
        }
    }
}
