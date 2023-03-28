<?php

namespace App\Model\Event\Listener\ORM;

use Doctrine\ORM\Event\PreRemoveEventArgs;

/**
 * Doctrine listener listening pre remove event.
 */
interface DoctrinePreRemoveEventListenerInterface
{

    /**
     * Handle doctrine pre remove event.
     *
     * @param PreRemoveEventArgs $args Pre remove event arguments.
     *
     * @return void
     */
    public function preRemove(PreRemoveEventArgs $args): void;

}