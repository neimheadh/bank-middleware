<?php

namespace App\Lifecycle\Entity;

use App\Model\Entity\RefreshedEntityInterface;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\Persistence\ObjectManager;

/**
 * Refreshed entities lifecycle listener.
 */
class RefreshedEntityLifecycleListener
{

    /**
     * Handle doctrine post load event.
     *
     * @param PostLoadEventArgs $args Event args.
     *
     * @return void
     */
    public function postLoad(PostLoadEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof RefreshedEntityInterface) {
            $this->refreshEntity($entity, $args->getObjectManager());
        }
    }

    /**
     * Refresh entity if necessary.
     *
     * @param RefreshedEntityInterface $entity  Loaded entity.
     * @param ObjectManager            $manager Entity manager.
     *
     * @return void
     */
    private function refreshEntity(
      RefreshedEntityInterface $entity,
      ObjectManager $manager
    ): void {
        if ($entity->needRefresh()) {
            $entity->refresh();
            $manager->persist($entity);
        }
    }

}