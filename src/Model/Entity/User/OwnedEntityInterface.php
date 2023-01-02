<?php

namespace App\Model\Entity\User;

use App\Entity\User\User;

/**
 * Entity having an owner.
 */
interface OwnedEntityInterface
{

    /**
     * Get owner.
     *
     * @return User|null
     */
    public function getOwner(): ?User;

    /**
     * Set owner.
     *
     * @param User $owner The owner.
     *
     * @return $this
     */
    public function setOwner(?User $owner): self;
}