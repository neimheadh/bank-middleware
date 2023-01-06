<?php

namespace App\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity with an enable switch trait.
 */
trait EnablingEntityTrait
{

    /**
     * Enable status.
     *
     * @var bool
     */
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $enabled = false;

    /**
     * {@inheritDoc}
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * {@inheritDoc}
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }
}