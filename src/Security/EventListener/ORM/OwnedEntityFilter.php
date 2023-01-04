<?php

namespace App\Security\EventListener\ORM;

use App\Model\Entity\User\OwnedEntityInterface;
use Sonata\AdminBundle\Event\ConfigureQueryEvent;
use Sonata\UserBundle\Model\UserInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Guard owned entities to be accessible only by their owner.
 */
class OwnedEntityFilter
{

    /**
     * @param Security $security Security helper.
     */
    public function __construct(
      private readonly Security $security
    ) {
    }

    /**
     * Filter owned entities on sonata queries.
     *
     * @param ConfigureQueryEvent $event Sonata query configuration event.
     *
     * @return void
     */
    public function filterSonataQueries(ConfigureQueryEvent $event): void
    {
        // Super admin access everything.
        if (in_array(
          UserInterface::ROLE_SUPER_ADMIN,
          $this->security->getUser()?->getRoles() ?: []
        )) {
            return;
        }

        $implements = class_implements($event->getAdmin()->getModelClass());

        if (in_array(OwnedEntityInterface::class, $implements)) {
            $qb = $event->getProxyQuery()->getQueryBuilder();
            $o = current($qb->getRootAliases());

            $qb->andWhere("$o.owner = :owner")
              ->setParameter('owner', $this->security->getUser());
        }
    }

}