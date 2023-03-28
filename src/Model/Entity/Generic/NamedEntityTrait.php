<?php

namespace App\Model\Entity\Generic;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity with name trait.
 */
trait NamedEntityTrait
{

    /**
     * Entity name.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 256, options: ['default' => ''])]
    private ?string $name = null;

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return "$this->name";
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

}