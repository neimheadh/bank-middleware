<?php

namespace App\Model\Event\Listener\ORM;

use Doctrine\ORM\Event\PrePersistEventArgs;

/**
 * Doctrine entity pre persist event listener.
 *
 * @template T as object
 */
interface DoctrinePrePersistEntityListenerInterface
{

    /**
     * Handle doctrine entity pre persist entity event.
     *
     * @param T                   $entity Persisted entity.
     * @param PrePersistEventArgs $args   Pre persist event arguments.
     *
     * @return void
     */
    public function prePersist(object $entity, PrePersistEventArgs $args): void;

}