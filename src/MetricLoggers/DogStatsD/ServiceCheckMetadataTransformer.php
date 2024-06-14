<?php

declare(strict_types=1);

namespace Rapide\Metrics\MetricLoggers\DogStatsD;

use Rapide\Metrics\Interfaces\ServiceCheckMetadataSanitizer as ServiceCheckMetadataSanitizerInterface;
use Rapide\Metrics\MetricLoggers\Exceptions\MetricLoggingFailureException;

class ServiceCheckMetadataTransformer implements ServiceCheckMetadataSanitizerInterface
{
    private ServiceCheckMetadataSanitizerInterface $serviceCheckMetadataSanitizer;

    public function __construct(ServiceCheckMetadataSanitizerInterface $serviceCheckMetadataSanitizer)
    {
        $this->serviceCheckMetadataSanitizer = $serviceCheckMetadataSanitizer;
    }

    /**
     * @throws MetricLoggingFailureException
     */
    public function sanitize(array $metadata): array
    {
        /*
         * The keys that are accepted as service check metadata match the keys that DogStatsD requires, so no
         * transformation is necessary.
         */
        return $this->serviceCheckMetadataSanitizer->sanitize($metadata);
    }
}
