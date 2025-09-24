<?php

declare(strict_types=1);

namespace MineAdminTest\Doctrine;

use MineAdmin\Doctrine\ConfigProvider;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    public function testConfigProviderReturnsArray(): void
    {
        $provider = new ConfigProvider();
        $config = $provider();
        
        $this->assertIsArray($config);
        $this->assertArrayHasKey('dependencies', $config);
        $this->assertArrayHasKey('commands', $config);
        $this->assertArrayHasKey('annotations', $config);
        $this->assertArrayHasKey('publish', $config);
    }
    
    public function testDependenciesConfiguration(): void
    {
        $provider = new ConfigProvider();
        $config = $provider();
        
        $this->assertArrayHasKey('dependencies', $config);
        $dependencies = $config['dependencies'];
        
        $this->assertArrayHasKey(\Doctrine\ORM\EntityManagerInterface::class, $dependencies);
    }
    
    public function testCommandsConfiguration(): void
    {
        $provider = new ConfigProvider();
        $config = $provider();
        
        $this->assertArrayHasKey('commands', $config);
        $commands = $config['commands'];
        
        $this->assertIsArray($commands);
        $this->assertGreaterThan(0, count($commands));
    }
}