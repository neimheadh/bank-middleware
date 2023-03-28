<?php

namespace App\Model\Entity\Generic;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity with default switch trait.
 */
trait DefaultEntityTrait
{

    /**
     * Default switch.
     *
     * @var bool
     */
    #[ORM\Column(
        name: 'is_default',
        type: 'boolean',
        unique: true,
        nullable: true
    )]
    private ?bool $default = null;

    /**
     * {@inheritDoc}
     */
    public function isDefault(): bool
    {
        return (bool)$this->default;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefault(bool $default): self
    {
        $this->default = $default ?: null;

        return $this;
    }

}