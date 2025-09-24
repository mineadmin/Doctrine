<?php

declare(strict_types=1);

/**
 * Default Doctrine configuration for development
 * This file provides sensible defaults for getting started with Doctrine ORM in Hyperf
 */

return [
    'doctrine' => [
        'dev_mode' => true,
        'proxy_dir' => BASE_PATH . '/runtime/proxies',
        'entity_paths' => [
            BASE_PATH . '/app/Entity',
        ],
        'connection' => [
            'driver' => 'pdo_mysql',
            'host' => 'localhost',
            'port' => 3306,
            'database' => 'hyperf',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
        ],
        'cache' => [
            'driver' => 'array',
        ],
        'migrations' => [
            'table_storage' => [
                'table_name' => 'doctrine_migration_versions',
            ],
            'migrations_paths' => [
                'App\Migration' => BASE_PATH . '/migrations',
            ],
        ],
    ],
];