<?php

namespace App\Model\Event\Listener\ORM;

/**
 * Auto configured doctrine entity listener.
 */
interface DoctrineEntityListenerInterface
{

    /**
     * Get entity class.
     *
     * @return string
     */
    public static function getEntityClass(): string;
}