<?php

declare(strict_types=1);

namespace MineAdmin\Doctrine\Listener;

use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;

/**
 * Timestampable listener that automatically sets created_at and updated_at timestamps
 * 
 * This listener works with entities that have createdAt and updatedAt properties.
 * It automatically sets these timestamps on persist and update operations.
 */
#[AsDoctrineListener(event: Events::prePersist)]
#[AsDoctrineListener(event: Events::preUpdate)]
class TimestampableListener
{
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();
        
        if (method_exists($entity, 'setCreatedAt') && method_exists($entity, 'getCreatedAt')) {
            if ($entity->getCreatedAt() === null) {
                $entity->setCreatedAt(new \DateTime());
            }
        }
        
        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new \DateTime());
        }
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
        
        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new \DateTime());
        }
    }
}