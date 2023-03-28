<?php

namespace App\Model\Entity\Generic;

/**
 * Application entity.
 */
interface EntityInterface extends DatedEntityInterface
{

    /**
     * Get entity id.
     *
     * @return int|null
     */
    public function getId(): ?int;
}