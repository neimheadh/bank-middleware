<?php

namespace App\Model\Entity\Generic;

use Stringable;

/**
 * Entity with name.
 */
interface NamedEntityInterface extends Stringable
{

    /**
     * Get entity name.
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Set entity name.
     *
     * @param string|null $name Entity name.
     *
     * @return $this
     */
    public function setName(?string $name): self;
}