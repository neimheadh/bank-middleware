<?php

namespace App\Model\Entity;

/**
 * Entity with an enable switch.
 */
interface EnablingEntityInterface
{

    /**
     * Is the entity enabled?
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Set entity enable status.
     *
     * @param bool $enabled Enable status.
     *
     * @return $this
     */
    public function setEnabled(bool $enabled): self;
}