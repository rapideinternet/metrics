<?php

declare(strict_types=1);

namespace Rapide\Metrics\Interfaces;

interface SupplierValueProvider
{
    public function getValue(): string;
}
