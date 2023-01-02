<?php

namespace App\Lifecycle\Entity;

use App\Model\Entity\User\OwnedEntityInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Entity with owner lifecycle listener.
 */
class OwnedEntityLifecycleListener
{

    /**
     * @param Security $security Application security helper.
     */
    public function __construct(
      private Security $security
    ) {
    }

    /**
     * Handle entities pre persist event.
     *
     * @param PrePersistEventArgs $args Event arguments.
     *
     * @return void
     */
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof OwnedEntityInterface) {
            $this->setEntityOwner($entity);
        }
    }

    /**
     * Set entity owner when not set.
     *
     * @param OwnedEntityInterface $entity Created entity.
     *
     * @return void
     */
    private function setEntityOwner(OwnedEntityInterface $entity): void
    {
        if ($entity->getOwner() === null
            && $this->security->getUser() !== null
        ) {
            $entity->setOwner($this->security->getUser());
        }
    }

}