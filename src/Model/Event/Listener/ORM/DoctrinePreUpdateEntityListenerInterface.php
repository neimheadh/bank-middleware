<?php

namespace App\Model\Event\Listener\ORM;

use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * Doctrine entity pre remove event listener.
 *
 * @template T as object
 */
interface DoctrinePreUpdateEntityListenerInterface
{

    /**
     * Handle doctrine pre update entity event.
     *
     * @param T                  $entity Removed entity.
     * @param PreUpdateEventArgs $args   Pre update event arguments.
     *
     * @return void
     */
    public function preUpdate(object $entity, PreUpdateEventArgs $args): void;

}