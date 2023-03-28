<?php

namespace App\Model\Event\Listener\ORM;

use Doctrine\ORM\Event\PreRemoveEventArgs;

/**
 * Doctrine entity pre remove event listener.
 *
 * @template T as object
 */
interface DoctrinePreRemoveEntityListenerInterface
{

    /**
     * Handle doctrine pre remove entity event.
     *
     * @param T                  $entity Removed entity.
     * @param PreRemoveEventArgs $args   Pre remove event arguments.
     *
     * @return void
     */
    public function preRemove(object $entity, PreRemoveEventArgs $args): void;
}