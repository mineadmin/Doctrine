<?php

declare(strict_types=1);

namespace MineAdmin\Doctrine\Command;

use Doctrine\ORM\Tools\SchemaTool;
use Hyperf\Command\Annotation\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[Command]
class UpdateSchemaCommand extends AbstractDoctrineCommand
{
    protected ?string $signature = 'doctrine:schema:update {--dump-sql : Instead of executing SQL, dump it to the console} {--force : Execute the update without confirmation}';
    protected string $description = 'Update database schema from entity mappings';

    public function handle(): int
    {
        $em = $this->getEntityManager();
        $metadata = $em->getMetadataFactory()->getAllMetadata();

        if (empty($metadata)) {
            $this->output->writeln('<info>No metadata found. Nothing to update.</info>');
            return self::SUCCESS;
        }

        $schemaTool = new SchemaTool($em);
        $sqls = $schemaTool->getUpdateSchemaSql($metadata, true);

        if (empty($sqls)) {
            $this->output->writeln('<info>Nothing to update - your database is already in sync with the current entity metadata.</info>');
            return self::SUCCESS;
        }

        if ($this->input->getOption('dump-sql')) {
            $this->output->writeln(implode(';' . PHP_EOL, $sqls) . ';');
            return self::SUCCESS;
        }

        if (!$this->input->getOption('force')) {
            $this->output->writeln('<comment>ATTENTION: This operation should not be executed in a production environment.</comment>');
            $this->output->writeln('');
            $this->output->writeln('<info>Would execute the following SQL queries:</info>');
            foreach ($sqls as $sql) {
                $this->output->writeln('    ' . $sql);
            }
            $this->output->writeln('');
            $this->output->writeln('<comment>Please run with --force to execute these queries</comment>');
            return self::SUCCESS;
        }

        $this->output->writeln('<info>Updating database schema...</info>');
        $schemaTool->updateSchema($metadata, true);
        $this->output->writeln('<info>Database schema updated successfully!</info>');

        return self::SUCCESS;
    }
}