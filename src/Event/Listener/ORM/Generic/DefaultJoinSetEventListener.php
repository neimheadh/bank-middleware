<?php

namespace App\Event\Listener\ORM\Generic;

use App\Model\Event\Listener\ORM\DoctrinePrePersistEventListenerInterface;
use App\Model\Event\Listener\ORM\DoctrinePreUpdateEventListenerInterface;
use App\Model\Repository\Generic\DefaultEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Default entities join value auto setter.
 */
class DefaultJoinSetEventListener implements
    DoctrinePrePersistEventListenerInterface,
    DoctrinePreUpdateEventListenerInterface
{

    /**
     * {@inheritDoc}
     */
    public function prePersist(PrePersistEventArgs $args): void
    {
        $this->joinDefaultEntities($args);
    }

    /**
     * {@inheritDoc}
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $this->joinDefaultEntities($args);
    }

    /**
     * Join default entities connected to the persisted/updated entity.
     *
     * @param LifecycleEventArgs $args Event arguments.
     *
     * @return void
     */
    private function joinDefaultEntities(LifecycleEventArgs $args): void
    {
        $manager = $args->getObjectManager();
        $entity = $args->getObject();

        if ($manager instanceof EntityManagerInterface) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $metadata = $manager->getClassMetadata(get_class($entity));

            foreach ($metadata->getAssociationMappings() as $property => $map) {
                if (($target = $map['targetEntity'] ?? null)
                    && $accessor->getValue($entity, $property) === null
                    && ($map['isOwningSide'] ?? null)
                ) {
                    $repository = $manager->getRepository($target);

                    if ($repository instanceof DefaultEntityRepositoryInterface) {
                        $accessor->setValue(
                            $entity,
                            $property,
                            $repository->findDefault()
                        );
                    }
                }
            }
        }
    }

}