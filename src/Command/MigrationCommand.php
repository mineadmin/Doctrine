<?php

declare(strict_types=1);

namespace MineAdmin\Doctrine\Command;

use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\Migrations\Tools\Console\Command\LatestCommand;
use Doctrine\Migrations\Tools\Console\Command\ListCommand;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\Migrations\Tools\Console\Command\RollupCommand;
use Doctrine\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\Migrations\Tools\Console\Command\SyncMetadataCommand;
use Doctrine\Migrations\Tools\Console\Command\UpToDateCommand;
use Doctrine\Migrations\Tools\Console\Command\VersionCommand;
use Hyperf\Command\Annotation\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[Command]
class MigrationCommand extends AbstractDoctrineCommand
{
    protected ?string $signature = 'doctrine:migration {action : The migration action (migrate, status, generate, diff, etc.)} {--dry-run : Execute the migration as a dry run} {version? : The version to migrate to}';
    protected string $description = 'Execute Doctrine migrations';

    public function handle(): int
    {
        $action = $this->input->getArgument('action');
        $migrationsConfig = $this->getDoctrineConfig()['migrations'] ?? [];
        
        if (empty($migrationsConfig['migrations_paths'])) {
            $this->output->writeln('<error>No migrations configuration found. Please configure migrations first.</error>');
            return self::FAILURE;
        }

        // Create DependencyFactory
        $dependencyFactory = DependencyFactory::fromEntityManager(
            new PhpFile($migrationsConfig['configuration_file'] ?? BASE_PATH . '/config/migrations.php'),
            new ExistingEntityManager($this->getEntityManager())
        );

        switch ($action) {
            case 'migrate':
                return $this->executeMigrate($dependencyFactory);
            case 'status':
                return $this->executeStatus($dependencyFactory);
            case 'generate':
                return $this->executeGenerate($dependencyFactory);
            case 'diff':
                return $this->executeDiff($dependencyFactory);
            default:
                $this->output->writeln('<error>Unknown migration action: ' . $action . '</error>');
                $this->output->writeln('<info>Available actions: migrate, status, generate, diff</info>');
                return self::FAILURE;
        }
    }

    protected function executeMigrate(DependencyFactory $dependencyFactory): int
    {
        $migrator = $dependencyFactory->getMigrator();
        $version = $this->input->getArgument('version');
        
        if ($this->input->getOption('dry-run')) {
            $this->output->writeln('<info>Executing migration in dry-run mode...</info>');
        }

        try {
            if ($version) {
                $migrator->migrate($version, $this->input->getOption('dry-run'));
            } else {
                $migrator->migrate(null, $this->input->getOption('dry-run'));
            }
            
            $this->output->writeln('<info>Migration completed successfully!</info>');
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->output->writeln('<error>Migration failed: ' . $e->getMessage() . '</error>');
            return self::FAILURE;
        }
    }

    protected function executeStatus(DependencyFactory $dependencyFactory): int
    {
        $statusCalculator = $dependencyFactory->getMigrationStatusCalculator();
        $migrationRepository = $dependencyFactory->getMigrationRepository();
        
        $status = $statusCalculator->getExecutionState();
        $availableMigrations = $migrationRepository->getMigrations();
        
        $this->output->writeln('<info>Migration Status:</info>');
        $this->output->writeln(' >> Configuration Source: ' . get_class($dependencyFactory->getConfiguration()));
        $this->output->writeln(' >> Available Migrations: ' . count($availableMigrations));
        $this->output->writeln(' >> Executed Migrations: ' . count($status->getExecutedMigrations()));
        $this->output->writeln(' >> Pending Migrations: ' . count($status->getAvailableMigrations()));
        
        return self::SUCCESS;
    }

    protected function executeGenerate(DependencyFactory $dependencyFactory): int
    {
        $generator = $dependencyFactory->getMigrationGenerator();
        
        try {
            $version = $generator->generateMigration();
            $this->output->writeln('<info>Generated new migration version: ' . $version . '</info>');
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->output->writeln('<error>Failed to generate migration: ' . $e->getMessage() . '</error>');
            return self::FAILURE;
        }
    }

    protected function executeDiff(DependencyFactory $dependencyFactory): int
    {
        $generator = $dependencyFactory->getMigrationGenerator();
        $schemaProvider = $dependencyFactory->getSchemaProvider();
        
        try {
            $fromSchema = $schemaProvider->createSchema();
            $toSchema = $dependencyFactory->getEntityManager()->getConnection()->createSchemaManager()->introspectSchema();
            
            $version = $generator->generateMigration();
            $this->output->writeln('<info>Generated new migration diff version: ' . $version . '</info>');
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->output->writeln('<error>Failed to generate diff migration: ' . $e->getMessage() . '</error>');
            return self::FAILURE;
        }
    }
}