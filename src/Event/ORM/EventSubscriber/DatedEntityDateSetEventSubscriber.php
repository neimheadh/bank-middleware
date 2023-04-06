<?php

namespace App\Event\ORM\EventSubscriber;

use App\Model\Entity\Generic\DatedEntityInterface;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

/**
 * Set dated entity creation and update date.
 */
class DatedEntityDateSetEventSubscriber implements EventSubscriberInterface
{

    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    /**
     * Handle doctrine pre persist event.
     *
     * Set created date to current date if null.
     *
     * @param PrePersistEventArgs $args Event arguments.
     *
     * @return void
     * @internal
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
     * Handle doctrine pre update event.
     *
     * Set update date to current date.
     *
     * @param PreUpdateEventArgs $args Event arguments.
     *
     * @return void
     * @internal
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof DatedEntityInterface) {
            $entity->setUpdatedAt(new DateTime());
        }
    }

}