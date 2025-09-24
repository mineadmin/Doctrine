<?php

declare(strict_types=1);

/**
 * Doctrine Migrations Configuration for Hyperf
 * 
 * This file contains the configuration specifically for Doctrine Migrations.
 * This is used by the Doctrine Migrations library for managing database schema changes.
 */

use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;

// This file should be used by the Doctrine Migrations DependencyFactory
// It's referenced in the migration commands and tools

return [
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

    'all_or_nothing' => true,
    'transactional' => true,
    'check_database_platform' => true,
    'organize_migrations' => 'year_and_month',

    'custom_template' => null,

    'connection' => null, // Use the default EntityManager's connection
    'em' => null, // Use the default EntityManager
];