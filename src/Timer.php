<?php

declare(strict_types=1);

namespace Rapide\Metrics;

use Rapide\Metrics\Exceptions\IntervalException;

final class Timer
{
    private float $start = 0.0;

    private float $stop = 0.0;

    public function __construct(float $start = null)
    {
        if (isset($start)) {
            $this->start = $start;
        }
    }

    public function start(): void
    {
        $this->start = microtime(true);
    }

    public function stop(): void
    {
        if ($this->start === 0.0) {
            throw new IntervalException('You must start interval before stopping');
        }
        $this->stop = microtime(true);
    }

    public function getInterval(): float
    {
        if ($this->stop === 0.0 || $this->start === 0.0) {
            throw new IntervalException('You must start and stop timer before getting interval');
        }

        return $this->stop - $this->start;
    }
}
