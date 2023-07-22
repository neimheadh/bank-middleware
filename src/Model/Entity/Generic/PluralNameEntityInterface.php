<?php

namespace App\Model\Entity\Generic;

use Neimheadh\SolidBundle\Doctrine\Entity\Generic\NamedEntityInterface;

/**
 * Entity with plural name.
 */
interface PluralNameEntityInterface extends NamedEntityInterface
{

    /**
     * Get plural name.
     *
     * @return string|null
     */
    public function getPluralName(): ?string;

    /**
     * Set plural name.
     *
     * @param string|null $name Plural name.
     *
     * @return $this
     */
    public function setPluralName(?string $name): self;
}