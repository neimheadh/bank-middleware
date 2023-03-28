<?php

namespace App\Model\Entity\Generic;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity with plural name trait.
 */
trait PluralNameEntityTrait
{
    use NamedEntityTrait;

    /**
     * Plural name.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 256, nullable: true)]
    private ?string $pluralName = null;

    /**
     * {@inheritDoc}
     */
    public function getPluralName(): ?string
    {
        return $this->pluralName;
    }

    /**
     * {@inheritDoc}
     */
    public function setPluralName(?string $pluralName): self
    {
        $this->pluralName = $pluralName;

        return $this;
    }
}