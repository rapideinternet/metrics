<?php

return [
    'enabled' => env('METRICS_ENABLED', false),

    'logger' => env('METRICS_LOGGER', 'file'),

    'namespace' => env('METRICS_NAMESPACE', ''),

    'datadog' => [
        'host' => env('DATADOG_HOST', 'datadog.localhost'),

        'port' => (int)env('DATADOG_PORT', 8125),

        'datadog_host' => env('DATADOG_REMOTE_HOST', 'https://app.datadoghq.eu'),

        'timeout' => (float)env('DATADOG_TIMEOUT', 5.0),
    ],

    'file' => [
        'file' => env('METRICS_LOG_FILE', storage_path('logs/metrics.log')),
    ],

    'components' => [
        // FQN class references of all your custom components
    ],
];
