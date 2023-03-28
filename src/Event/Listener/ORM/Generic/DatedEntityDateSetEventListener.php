<?php

namespace App\Event\Listener\ORM\Generic;

use App\Model\Entity\Generic\DatedEntityInterface;
use App\Model\Event\Listener\ORM\DoctrinePrePersistEventListenerInterface;
use App\Model\Event\Listener\ORM\DoctrinePreUpdateEventListenerInterface;
use DateTime;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * Set dated entity creation and update date.
 */
class DatedEntityDateSetEventListener implements
    DoctrinePrePersistEventListenerInterface,
    DoctrinePreUpdateEventListenerInterface
{

    /**
     * {@inheritDoc}
     */
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof DatedEntityInterface
            && $entity->getCreatedAt() === null
        ) {
            $entity->setCreatedAt(new DateTime());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof DatedEntityInterface) {
            $entity->setUpdatedAt(new DateTime());
        }
    }

}