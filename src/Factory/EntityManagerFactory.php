<?php

declare(strict_types=1);

namespace MineAdmin\Doctrine\Factory;

use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\ContainerInterface;
use Psr\Container\ContainerInterface as PsrContainerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class EntityManagerFactory
{
    public function __invoke(PsrContainerInterface $container): EntityManagerInterface
    {
        $config = $container->get(ConfigInterface::class);
        $doctrineConfig = $config->get('doctrine', []);

        // Setup Doctrine Configuration
        $isDevMode = $doctrineConfig['dev_mode'] ?? false;
        $proxyDir = $doctrineConfig['proxy_dir'] ?? BASE_PATH . '/runtime/proxies';
        $entityPaths = $doctrineConfig['entity_paths'] ?? [BASE_PATH . '/app/Entity'];

        // Create cache
        $cache = $this->createCache($doctrineConfig['cache'] ?? []);
        
        // Setup ORM Configuration
        $ormConfig = ORMSetup::createAttributeMetadataConfiguration(
            $entityPaths,
            $isDevMode,
            $proxyDir,
            $cache
        );

        // Set up custom configurations
        if (isset($doctrineConfig['naming_strategy'])) {
            $namingStrategy = $container->get($doctrineConfig['naming_strategy']);
            $ormConfig->setNamingStrategy($namingStrategy);
        }

        if (isset($doctrineConfig['quote_strategy'])) {
            $quoteStrategy = $container->get($doctrineConfig['quote_strategy']);
            $ormConfig->setQuoteStrategy($quoteStrategy);
        }

        // Setup Database Connection
        $connectionParams = $this->getConnectionParams($doctrineConfig['connection'] ?? []);
        $connection = DriverManager::getConnection($connectionParams, $ormConfig);

        // Create EntityManager
        return new EntityManager($connection, $ormConfig);
    }

    protected function createCache(array $cacheConfig): ?\Psr\Cache\CacheItemPoolInterface
    {
        $driver = $cacheConfig['driver'] ?? 'array';

        return match ($driver) {
            'filesystem' => new FilesystemAdapter(
                'doctrine',
                0,
                $cacheConfig['path'] ?? BASE_PATH . '/runtime/cache/doctrine'
            ),
            'array' => new ArrayAdapter(),
            default => null,
        };
    }

    protected function getConnectionParams(array $connectionConfig): array
    {
        // Support both DSN and individual parameters
        if (isset($connectionConfig['url'])) {
            $dsnParser = new DsnParser([
                'mysql' => 'pdo_mysql',
                'mysqli' => 'pdo_mysql',
                'pgsql' => 'pdo_pgsql',
                'postgres' => 'pdo_pgsql',
                'postgresql' => 'pdo_pgsql',
                'sqlite' => 'pdo_sqlite',
                'sqlite3' => 'pdo_sqlite',
            ]);
            
            return $dsnParser->parse($connectionConfig['url']);
        }

        // Fallback to individual parameters
        return [
            'driver' => $connectionConfig['driver'] ?? 'pdo_mysql',
            'host' => $connectionConfig['host'] ?? 'localhost',
            'port' => $connectionConfig['port'] ?? 3306,
            'dbname' => $connectionConfig['database'] ?? 'test',
            'user' => $connectionConfig['username'] ?? 'root',
            'password' => $connectionConfig['password'] ?? '',
            'charset' => $connectionConfig['charset'] ?? 'utf8mb4',
        ];
    }
}