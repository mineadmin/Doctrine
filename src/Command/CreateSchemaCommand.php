<?php

declare(strict_types=1);

namespace MineAdmin\Doctrine\Command;

use Doctrine\ORM\Tools\SchemaTool;
use Hyperf\Command\Annotation\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[Command]
class CreateSchemaCommand extends AbstractDoctrineCommand
{
    protected ?string $signature = 'doctrine:schema:create {--dump-sql : Instead of executing SQL, dump it to the console}';
    protected string $description = 'Create database schema from entity mappings';

    public function handle(): int
    {
        $em = $this->getEntityManager();
        $metadata = $em->getMetadataFactory()->getAllMetadata();

        if (empty($metadata)) {
            $this->output->writeln('<info>No metadata found. Nothing to create.</info>');
            return self::SUCCESS;
        }

        $schemaTool = new SchemaTool($em);
        $sqls = $schemaTool->getCreateSchemaSql($metadata);

        if ($this->input->getOption('dump-sql')) {
            $this->output->writeln(implode(';' . PHP_EOL, $sqls) . ';');
            return self::SUCCESS;
        }

        $this->output->writeln('<info>Creating database schema...</info>');
        $schemaTool->createSchema($metadata);
        $this->output->writeln('<info>Database schema created successfully!</info>');

        return self::SUCCESS;
    }
}