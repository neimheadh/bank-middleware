<?php

namespace App\Model\Entity;

/**
 * Entity with string uid.
 */
interface UidEntityInterface
{

    /**
     * Get entity uid.
     *
     * @return string|null
     */
    public function getUid(): ?string;

    /**
     * Set entity uid.
     *
     * @param string|null $uid Entity uid.
     *
     * @return $this
     */
    public function setUid(?string $uid): self;
}