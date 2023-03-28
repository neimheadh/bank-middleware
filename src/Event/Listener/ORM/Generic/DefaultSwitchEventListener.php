<?php

namespace App\Event\Listener\ORM\Generic;

use App\Model\Entity\Generic\DefaultEntityInterface;
use App\Model\Event\Listener\ORM\DoctrinePrePersistEventListenerInterface;
use App\Model\Event\Listener\ORM\DoctrinePreUpdateEventListenerInterface;
use App\Model\Repository\Generic\DefaultEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * Switch default switch value for DefaultSwitchEntity.
 */
class DefaultSwitchEventListener implements
    DoctrinePrePersistEventListenerInterface,
    DoctrinePreUpdateEventListenerInterface
{

    /**
     * {@inheritDoc}
     */
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof DefaultEntityInterface
            && $entity->isDefault()
        ) {
            $this->switchDefault($entity, $args->getObjectManager());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof DefaultEntityInterface
            && $args->hasChangedField('default')
            && $entity->isDefault()
        ) {
            $this->switchDefault(
                $entity,
                $args->getObjectManager()
            );
        }
    }

    /**
     * Switch currently default switch if the given entity is default.
     *
     * @param DefaultEntityInterface $entity  The entity.
     * @param EntityManagerInterface $manager Object manager.
     *
     * @return void
     */
    private function switchDefault(
        DefaultEntityInterface $entity,
        EntityManagerInterface $manager
    ): void {
        $repository = $manager->getRepository($entity::class);

        if ($entity->isDefault()) {
            /** @var DefaultEntityInterface $default */
            $default = $repository instanceof DefaultEntityRepositoryInterface
                ? $repository->findDefault()
                : $repository->findOneBy(['default' => true]);

            $default?->setDefault($default === $entity);
            $classname = get_class($entity);

            foreach (
                $manager->getUnitOfWork()->getScheduledEntityInsertions()
                as $inserted
            ) {
                if ($inserted instanceof $classname) {
                    $inserted->setDefault($inserted === $entity);
                }
            }

            foreach (
                $manager->getUnitOfWork()->getScheduledEntityUpdates()
                as $updated
            ) {
                if ($updated instanceof $classname) {
                    $updated->setDefault($updated === $entity);
                }
            }
        }
    }

}