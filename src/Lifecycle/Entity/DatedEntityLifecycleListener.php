<?php

namespace App\Lifecycle\Entity;

use App\Model\Entity\DatedEntityInterface;
use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

/**
 * Dated entity update & creation date listener.
 */
class DatedEntityLifecycleListener
{

    /**
     * Handle dated entity persist.
     *
     * Set creation date.
     *
     * @param PrePersistEventArgs $args Event arguments.
     *
     * @return void
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
     * Handle dated entity update.
     *
     * Set update date.
     *
     * @param PreUpdateEventArgs $args Event arguments.
     *
     * @return void
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof DatedEntityInterface) {
            $entity->setUpdatedAt(new DateTime());
        }
    }

}