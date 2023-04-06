<?php

namespace App\Event\ORM\EventSubscriber;

use App\Model\Repository\Generic\DefaultEntityRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Default entities join value auto setter.
 */
class DefaultJoinSetEventSubscriber implements EventSubscriberInterface
{

    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate
        ];
    }

    /**
     * Handle doctrine pre persist event.
     *
     * @param PrePersistEventArgs $args Event arguments.
     *
     * @return void
     * @internal
     */
    public function prePersist(PrePersistEventArgs $args): void
    {
        $this->joinDefaultEntities($args);
    }

    /**
     * Handle doctrine pre update event.
     *
     * @param PreUpdateEventArgs $args Event arguments.
     *
     * @return void
     * @internal
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