<?php

declare(strict_types=1);

namespace MineAdmin\Doctrine\Command;

use Doctrine\ORM\Tools\SchemaTool;
use Hyperf\Command\Annotation\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[Command]
class DropSchemaCommand extends AbstractDoctrineCommand
{
    protected ?string $signature = 'doctrine:schema:drop {--dump-sql : Instead of executing SQL, dump it to the console} {--force : Execute the drop without confirmation}';
    protected string $description = 'Drop database schema from entity mappings';

    public function handle(): int
    {
        $em = $this->getEntityManager();
        $metadata = $em->getMetadataFactory()->getAllMetadata();

        if (empty($metadata)) {
            $this->output->writeln('<info>No metadata found. Nothing to drop.</info>');
            return self::SUCCESS;
        }

        $schemaTool = new SchemaTool($em);
        $sqls = $schemaTool->getDropSchemaSql($metadata);

        if ($this->input->getOption('dump-sql')) {
            $this->output->writeln(implode(';' . PHP_EOL, $sqls) . ';');
            return self::SUCCESS;
        }

        if (!$this->input->getOption('force')) {
            $this->output->writeln('<error>ATTENTION: This operation will permanently delete all data in your database!</error>');
            $this->output->writeln('');
            $this->output->writeln('<info>Would execute the following SQL queries:</info>');
            foreach ($sqls as $sql) {
                $this->output->writeln('    ' . $sql);
            }
            $this->output->writeln('');
            $this->output->writeln('<comment>Please run with --force to execute these queries</comment>');
            return self::SUCCESS;
        }

        $this->output->writeln('<info>Dropping database schema...</info>');
        $schemaTool->dropSchema($metadata);
        $this->output->writeln('<info>Database schema dropped successfully!</info>');

        return self::SUCCESS;
    }
}