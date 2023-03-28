<?php

namespace App\Model\Entity\Generic;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity with code trait.
 */
trait CodeEntityTrait
{

    /**
     * Entity code.
     *
     * @var string|null
     */
    #[ORM\Column(
        type: 'string',
        length: 32,
        unique: true,
        options: ['fixed' => true],
    )]
    private ?string $code = null;

    /**
     * {@inheritDoc}
     */
    public function getCode(): ?string
    {
        return trim($this->code);
    }

    /**
     * {@inheritDoc}
     */
    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }
}