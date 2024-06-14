<?php

declare(strict_types=1);

namespace Rapide\Metrics\Interfaces;

interface ClassShortener
{
    /**
     * @param class-string|string $className
     */
    public function shorten(string $className): string;
}
