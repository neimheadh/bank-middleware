<?php

namespace App\Model\Entity\Generic;

/**
 * Entity with code.
 */
interface CodeEntityInterface
{

    /**
     * Get entity code.
     *
     * @return string|null
     */
    public function getCode(): ?string;

    /**
     * Set entity code.
     *
     * @param string|null $code Entity code.
     *
     * @return $this
     */
    public function setCode(?string $code): self;
}