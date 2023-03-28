<?php

namespace App\Model\Event\Listener\ORM;

use Doctrine\ORM\Event\PrePersistEventArgs;

/**
 * Doctrine listener listening pre persist event.
 */
interface DoctrinePrePersistEventListenerInterface
{

    /**
     * Handle doctrine pre persist event.
     *
     * @param PrePersistEventArgs $args Pre persist event arguments.
     *
     * @return void
     */
    public function prePersist(PrePersistEventArgs $args): void;
}