<?php

declare(strict_types=1);

/**
 * Example: Basic Entity Definition
 * 
 * This example shows how to create a basic entity using the Doctrine component.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use MineAdmin\Doctrine\Entity\AbstractEntity;

#[ORM\Entity]
#[ORM\Table(name: 'products')]
class Product extends AbstractEntity
{
    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private string $price;

    #[ORM\Column(type: 'boolean')]
    private bool $active = true;

    // Constructor
    public function __construct(string $name, string $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    // Getters and Setters
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }
}