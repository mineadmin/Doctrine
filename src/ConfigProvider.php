<?php

declare(strict_types=1);

namespace MineAdmin\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use MineAdmin\Doctrine\Command\ClearCacheCommand;
use MineAdmin\Doctrine\Command\CreateSchemaCommand;
use MineAdmin\Doctrine\Command\DropSchemaCommand;
use MineAdmin\Doctrine\Command\MigrationCommand;
use MineAdmin\Doctrine\Command\UpdateSchemaCommand;
use MineAdmin\Doctrine\Factory\EntityManagerFactory;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                EntityManagerInterface::class => EntityManagerFactory::class,
            ],
            'commands' => [
                ClearCacheCommand::class,
                CreateSchemaCommand::class,
                DropSchemaCommand::class,
                UpdateSchemaCommand::class,
                MigrationCommand::class,
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'doctrine-config',
                    'description' => 'The configuration file for Doctrine.',
                    'source' => __DIR__ . '/../publish/doctrine.php',
                    'destination' => BASE_PATH . '/config/autoload/doctrine.php',
                ],
                [
                    'id' => 'doctrine-migrations',
                    'description' => 'The configuration file for Doctrine Migrations.',
                    'source' => __DIR__ . '/../publish/migrations.php',
                    'destination' => BASE_PATH . '/config/autoload/migrations.php',
                ],
            ],
        ];
    }
}