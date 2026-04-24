<?php

return [
    'debug' => true,

    'App' => [
        'namespace' => 'App',
        'encoding' => 'UTF-8',
        'defaultLocale' => 'en_US',
        'defaultTimezone' => 'UTC',
        'base' => false,
        'dir' => 'src',
        'webroot' => 'webroot',
        'wwwRoot' => WWW_ROOT,
        'fullBaseUrl' => false,
        'imageBaseUrl' => 'img/',
        'cssBaseUrl' => 'css/',
        'jsBaseUrl' => 'js/',
        'paths' => [
            'plugins' => [ROOT . DS . 'plugins' . DS],
            'templates' => [ROOT . DS . 'templates' . DS],
            'locales' => [RESOURCES . 'locales' . DS],
        ],
    ],

    'Datasources' => [
        'default' => [
            'className' => \Cake\Database\Connection::class,
            'driver' => \Cake\Database\Driver\Sqlite::class,
            'database' => ROOT . DS . 'database.sqlite',
            'encoding' => 'utf8',
            'cacheMetadata' => true,
        ],
    ],

    'Log' => [
        'debug' => [
            'className' => \Cake\Log\Engine\FileLog::class,
            'path' => LOGS,
            'file' => 'debug',
            'levels' => ['notice', 'info', 'debug'],
        ],
        'error' => [
            'className' => \Cake\Log\Engine\FileLog::class,
            'path' => LOGS,
            'file' => 'error',
            'levels' => ['warning', 'error', 'critical', 'alert', 'emergency'],
        ],
    ],

    'Cache' => [
        '_cake_core_' => [
            'className' => \Cake\Cache\Engine\FileEngine::class,
            'prefix' => 'cake_core_',
            'path' => CACHE . 'persistent' . DS,
            'serialize' => true,
            'duration' => '+1 years',
        ],
        '_cake_model_' => [
            'className' => \Cake\Cache\Engine\FileEngine::class,
            'prefix' => 'cake_model_',
            'path' => CACHE . 'models' . DS,
            'serialize' => true,
            'duration' => '+1 years',
        ],
    ],

    'Error' => [
        'errorLevel' => E_ALL,
        'skipLog' => [],
        'log' => true,
        'trace' => true,
    ],

    'Security' => [
        'salt' => 'service-runner-cakephp-sample-salt-change-in-production',
    ],
];
