<?php

declare(strict_types=1);

namespace MineAdmin\Doctrine\Command;

use Doctrine\ORM\EntityManagerInterface;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

abstract class AbstractDoctrineCommand extends HyperfCommand
{
    protected EntityManagerInterface $entityManager;
    protected ConfigInterface $config;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->config = $container->get(ConfigInterface::class);
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    protected function getDoctrineConfig(): array
    {
        return $this->config->get('doctrine', []);
    }
}