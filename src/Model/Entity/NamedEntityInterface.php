<?php

namespace App\Model\Entity;

use Stringable;

/**
 * Entity with name attribute.
 */
interface NamedEntityInterface extends Stringable
{

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Set name.
     *
     * @param string|null $name Entity name.
     *
     * @return $this
     */
    public function setName(?string $name): self;
}