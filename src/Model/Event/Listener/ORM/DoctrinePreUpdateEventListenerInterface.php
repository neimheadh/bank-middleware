<?php

namespace App\Model\Event\Listener\ORM;

use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * Doctrine listener listening pre update event.
 */
interface DoctrinePreUpdateEventListenerInterface
{

    /**
     * Handle doctrine pre update event.
     *
     * @param PreUpdateEventArgs $args Pre update event arguments.
     *
     * @return void
     */
    public function preUpdate(PreUpdateEventArgs $args): void;

}