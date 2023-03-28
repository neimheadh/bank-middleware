<?php

namespace App\Model\Entity\Generic;

/**
 * Entity with default switch.
 */
interface DefaultEntityInterface
{

    /**
     * Get default value.
     *
     * @return bool
     */
    public function isDefault(): bool;

    /**
     * Change default status.
     *
     * @param bool $default Is entity default.
     *
     * @return $this
     */
    public function setDefault(bool $default): self;
}