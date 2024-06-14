<?php

declare(strict_types=1);

namespace Rapide\Metrics;

final class Counter
{
    private int $value = 0;

    public function increment(): void
    {
        $this->value++;
    }

    public function decrement(): void
    {
        $this->value--;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
