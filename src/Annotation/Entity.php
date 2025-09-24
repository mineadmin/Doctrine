<?php

declare(strict_types=1);

namespace MineAdmin\Doctrine\Annotation;

use Doctrine\ORM\Mapping\Entity as DoctrineEntity;

/**
 * Hyperf-compatible Entity annotation that extends Doctrine's Entity
 * 
 * This annotation can be used to mark classes as Doctrine entities
 * while maintaining compatibility with Hyperf's annotation system.
 * 
 * @Annotation
 * @Target("CLASS")
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class Entity extends DoctrineEntity
{
    // This class simply extends Doctrine's Entity annotation
    // to provide Hyperf-specific functionality if needed in the future
}