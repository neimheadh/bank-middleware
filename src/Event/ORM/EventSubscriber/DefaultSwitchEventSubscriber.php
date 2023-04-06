<?php

namespace App\Event\ORM\EventSubscriber;

use App\Model\Entity\Generic\DefaultEntityInterface;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ObjectManager;

/**
 * Switch default switch value for DefaultSwitchEntity.
 */
class DefaultSwitchEventSubscriber implements EventSubscriberInterface
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
     * @param PrePersistEventArgs $args Event arguments.
     *
     * @return void
     * @throws ORMException
     * @internal
     */
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();
        $manager = $args->getObjectManager();

        if ($entity instanceof DefaultEntityInterface
            && $entity->isDefault()
        ) {
            $this->switchDefault($entity, $manager);
        }
    }

    /**
     * Handle doctrine pre update event.
     *
     * @param PreUpdateEventArgs $args Event arguments.
     *
     * @return void
     * @throws ORMException
     * @internal
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
        $manager = $args->getObjectManager();

        if ($entity instanceof DefaultEntityInterface
            && $entity->isDefault()
        ) {
            $this->switchDefault(
                $entity,
                $manager
            );
        }
    }

    /**
     * Set null currently default switch.
     *
     * @param DefaultEntityInterface $entity  The entity.
     * @param ObjectManager          $manager Object manager.
     *
     * @return void
     * @throws ORMException
     */
    private function switchDefault(
        DefaultEntityInterface $entity,
        ObjectManager $manager
    ): void {
        $manager->createQueryBuilder()
            ->update($entity::class, 'e')
            ->set('e.default', ':default')
            ->getQuery()->execute(['default' => null]);
    }

}