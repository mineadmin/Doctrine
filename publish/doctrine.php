<?php

declare(strict_types=1);

/**
 * Doctrine ORM Configuration for Hyperf
 * 
 * This file contains the configuration for Doctrine ORM integration with Hyperf framework.
 * Copy this file to config/autoload/doctrine.php to customize your Doctrine setup.
 */

return [
    'doctrine' => [
        // Development mode - set to false in production
        'dev_mode' => env('APP_ENV', 'dev') !== 'prod',
        
        // Proxy directory for generated proxy classes
        'proxy_dir' => BASE_PATH . '/runtime/proxies',
        
        // Entity paths - directories containing your entity classes
        'entity_paths' => [
            BASE_PATH . '/app/Entity',
        ],
        
        // Database connection configuration
        'connection' => [
            // Option 1: Use Database URL (recommended)
            'url' => env('DATABASE_URL', 'mysql://root:@localhost:3306/hyperf'),
            
            // Option 2: Individual connection parameters (alternative to URL)
            // 'driver' => 'pdo_mysql',
            // 'host' => env('DB_HOST', 'localhost'),
            // 'port' => env('DB_PORT', 3306),
            // 'database' => env('DB_DATABASE', 'hyperf'),
            // 'username' => env('DB_USERNAME', 'root'),
            // 'password' => env('DB_PASSWORD', ''),
            // 'charset' => env('DB_CHARSET', 'utf8mb4'),
            
            // Additional DBAL options
            'options' => [
                // PDO options
            ],
        ],
        
        // Cache configuration
        'cache' => [
            'driver' => env('DOCTRINE_CACHE_DRIVER', 'filesystem'), // array, filesystem
            'path' => BASE_PATH . '/runtime/cache/doctrine', // for filesystem driver
        ],
        
        // Custom naming strategy (optional)
        // 'naming_strategy' => \Doctrine\ORM\Mapping\UnderscoreNamingStrategy::class,
        
        // Custom quote strategy (optional)
        // 'quote_strategy' => \Doctrine\ORM\Mapping\DefaultQuoteStrategy::class,
        
        // Custom repository factory (optional)
        // 'repository_factory' => \Doctrine\ORM\Repository\DefaultRepositoryFactory::class,
        
        // Event listeners and subscribers
        'event_listeners' => [
            // Add your event listeners here
            // 'eventName' => ['listener1', 'listener2'],
        ],
        
        'event_subscribers' => [
            // Add your event subscribers here
            // 'subscriber1',
        ],
        
        // Custom types
        'types' => [
            // 'custom_type' => 'App\Doctrine\Type\CustomType',
        ],
        
        // Custom functions
        'string_functions' => [
            // 'CUSTOM_FUNC' => 'App\Doctrine\Function\CustomFunction',
        ],
        
        'numeric_functions' => [
            // 'CUSTOM_NUMERIC' => 'App\Doctrine\Function\CustomNumericFunction',
        ],
        
        'datetime_functions' => [
            // 'CUSTOM_DATE' => 'App\Doctrine\Function\CustomDateFunction',
        ],
        
        // Second level cache (optional)
        'second_level_cache' => [
            'enabled' => false,
            'default_lifetime' => 3600,
            'default_lock_lifetime' => 60,
            'file_lock_region_directory' => BASE_PATH . '/runtime/cache/doctrine_locks',
            'regions' => [
                // 'my_entity_region' => [
                //     'lifetime' => 7200,
                // ],
            ],
        ],
        
        // Migrations configuration (will be extended in migrations.php)
        'migrations' => [
            'table_storage' => [
                'table_name' => 'doctrine_migration_versions',
                'version_column_name' => 'version',
                'version_column_length' => 1024,
                'executed_at_column_name' => 'executed_at',
                'execution_time_column_name' => 'execution_time',
            ],
            'migrations_paths' => [
                'App\Migration' => BASE_PATH . '/migrations',
            ],
            'organize_migrations' => 'year_and_month', // none, year, year_and_month
            'check_database_platform' => true,
            'transactional' => true,
            'all_or_nothing' => true,
        ],
    ],
];