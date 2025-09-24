# Doctrine Component for Hyperf

[![Latest Version](https://img.shields.io/packagist/v/mineadmin/doctrine.svg)](https://packagist.org/packages/mineadmin/doctrine)
[![Total Downloads](https://img.shields.io/packagist/dt/mineadmin/doctrine.svg)](https://packagist.org/packages/mineadmin/doctrine)
[![License](https://img.shields.io/packagist/l/mineadmin/doctrine.svg)](https://packagist.org/packages/mineadmin/doctrine)

A comprehensive Doctrine ORM integration component for the Hyperf framework, providing seamless database operations with the power of Doctrine ORM.

## Features

- 🚀 **Full Doctrine ORM Integration** - Complete Doctrine ORM support with Hyperf
- 🔧 **Easy Configuration** - Simple configuration through Hyperf's config system
- 📦 **Migration Support** - Built-in database migration commands
- 🎯 **Console Commands** - Rich set of CLI commands for database management
- 🏗️ **Entity Generation** - Support for entity generation and schema management
- ⚡ **Performance Optimized** - Caching support and proxy generation
- 🧪 **Testing Ready** - Full testing support with PHPUnit integration

## Installation

Install the package via Composer:

```bash
composer require mineadmin/doctrine
```

## Quick Start

### 1. Publish Configuration

Publish the configuration files to customize your Doctrine setup:

```bash
php bin/hyperf.php vendor:publish mineadmin/doctrine
```

This will create:
- `config/autoload/doctrine.php` - Main Doctrine configuration
- `config/autoload/migrations.php` - Migrations configuration

### 2. Configure Database Connection

Edit `config/autoload/doctrine.php`:

```php
<?php
return [
    'doctrine' => [
        'connection' => [
            'url' => env('DATABASE_URL', 'mysql://root:@localhost:3306/your_database'),
            // or use individual parameters:
            // 'driver' => 'pdo_mysql',
            // 'host' => env('DB_HOST', 'localhost'),
            // 'database' => env('DB_DATABASE', 'hyperf'),
            // ... etc
        ],
        'entity_paths' => [
            BASE_PATH . '/app/Entity',
        ],
    ],
];
```

### 3. Create Your First Entity

Create an entity in `app/Entity/User.php`:

```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use MineAdmin\Doctrine\Entity\AbstractEntity;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User extends AbstractEntity
{
    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $email;

    // Getters and setters...
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
}
```

### 4. Create Database Schema

Generate and run migrations:

```bash
# Create the database schema
php bin/hyperf.php doctrine:schema:create

# Or generate a migration
php bin/hyperf.php doctrine:migration generate
php bin/hyperf.php doctrine:migration migrate
```

### 5. Use in Your Application

Inject the EntityManager in your services:

```php
<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Hyperf\Di\Annotation\Inject;

class UserService
{
    #[Inject]
    private EntityManagerInterface $entityManager;

    public function createUser(string $name, string $email): User
    {
        $user = new User();
        $user->setName($name);
        $user->setEmail($email);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $email]);
    }
}
```

## Available Console Commands

The component provides several console commands for database management:

### Schema Management
- `doctrine:schema:create` - Create database schema
- `doctrine:schema:update` - Update database schema
- `doctrine:schema:drop` - Drop database schema

### Migrations
- `doctrine:migration migrate` - Execute migrations
- `doctrine:migration status` - Show migration status
- `doctrine:migration generate` - Generate a new migration
- `doctrine:migration diff` - Generate migration by comparing schemas

### Cache Management
- `doctrine:cache:clear` - Clear Doctrine caches

## Configuration

### Basic Configuration

```php
<?php
return [
    'doctrine' => [
        // Development mode
        'dev_mode' => env('APP_ENV') !== 'prod',
        
        // Proxy directory
        'proxy_dir' => BASE_PATH . '/runtime/proxies',
        
        // Entity paths
        'entity_paths' => [
            BASE_PATH . '/app/Entity',
        ],
        
        // Database connection
        'connection' => [
            'url' => env('DATABASE_URL'),
        ],
        
        // Cache configuration
        'cache' => [
            'driver' => 'filesystem', // array, filesystem
            'path' => BASE_PATH . '/runtime/cache/doctrine',
        ],
    ],
];
```

### Advanced Configuration

```php
<?php
return [
    'doctrine' => [
        // Custom naming strategy
        'naming_strategy' => \Doctrine\ORM\Mapping\UnderscoreNamingStrategy::class,
        
        // Event listeners
        'event_listeners' => [
            'preUpdate' => ['App\Listener\MyListener'],
        ],
        
        // Event subscribers
        'event_subscribers' => [
            'App\Subscriber\MySubscriber',
        ],
        
        // Custom types
        'types' => [
            'uuid' => 'App\Doctrine\Type\UuidType',
        ],
        
        // Custom functions
        'string_functions' => [
            'CUSTOM_FUNC' => 'App\Doctrine\Function\CustomFunction',
        ],
    ],
];
```

## Testing

Run the test suite:

```bash
composer test
```

## Migration from Other ORMs

### From Hyperf Database

1. Export your existing schema
2. Create corresponding Doctrine entities
3. Use migration commands to sync schemas
4. Update your repositories to use EntityManager

### Best Practices

1. **Use Attributes/Annotations**: Prefer PHP 8 attributes over YAML/XML configuration
2. **Repository Pattern**: Create custom repositories for complex queries
3. **Entity Validation**: Use Symfony Validator for entity validation
4. **Lazy Loading**: Configure proxy generation for performance
5. **Migrations**: Always use migrations for schema changes

## Contributing

1. Fork the repository
2. Create a feature branch
3. Add tests for your changes
4. Ensure all tests pass
5. Submit a pull request

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Support

- [Documentation](https://github.com/mineadmin/Doctrine/wiki)
- [Issues](https://github.com/mineadmin/Doctrine/issues)
- [Discussions](https://github.com/mineadmin/Doctrine/discussions)

## Credits

- [MineAdmin Team](https://github.com/mineadmin)
- [Doctrine Project](https://www.doctrine-project.org/)
- [Hyperf Framework](https://hyperf.io/)
