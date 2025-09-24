<?php

declare(strict_types=1);

namespace MineAdmin\Doctrine\Command;

use Hyperf\Command\Annotation\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[Command]
class ClearCacheCommand extends AbstractDoctrineCommand
{
    protected ?string $signature = 'doctrine:cache:clear {--query : Clear query cache} {--result : Clear result cache} {--metadata : Clear metadata cache}';
    protected string $description = 'Clear Doctrine cache';

    public function handle(): int
    {
        $em = $this->getEntityManager();
        $configuration = $em->getConfiguration();

        $clearQuery = $this->input->getOption('query');
        $clearResult = $this->input->getOption('result');
        $clearMetadata = $this->input->getOption('metadata');

        // If no specific cache type is specified, clear all
        if (!$clearQuery && !$clearResult && !$clearMetadata) {
            $clearQuery = $clearResult = $clearMetadata = true;
        }

        $cleared = [];

        if ($clearQuery && ($queryCache = $configuration->getQueryCache())) {
            $queryCache->clear();
            $cleared[] = 'query';
        }

        if ($clearResult && ($resultCache = $configuration->getResultCache())) {
            $resultCache->clear();
            $cleared[] = 'result';
        }

        if ($clearMetadata && ($metadataCache = $configuration->getMetadataCache())) {
            $metadataCache->clear();
            $cleared[] = 'metadata';
        }

        if (empty($cleared)) {
            $this->output->writeln('<info>No cache to clear.</info>');
        } else {
            $this->output->writeln('<info>Cleared ' . implode(', ', $cleared) . ' cache(s).</info>');
        }

        return self::SUCCESS;
    }
}