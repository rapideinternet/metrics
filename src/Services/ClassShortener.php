<?php

declare(strict_types=1);

namespace Rapide\Metrics\Services;

use ReflectionClass;

final class ClassShortener implements \Rapide\Metrics\Interfaces\ClassShortener
{
    /**
     * @param class-string|string $className
     */
    public function shorten(string $className): string
    {
        if (class_exists($className)) {
            return (new ReflectionClass($className))->getShortName();
        }

        return $className;
    }
}
