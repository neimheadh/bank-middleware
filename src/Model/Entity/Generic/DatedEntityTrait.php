<?php

namespace App\Model\Entity\Generic;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity with creation et update date trait.
 */
trait DatedEntityTrait
{

    /**
     * Creation date.
     *
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?DateTimeInterface $createdAt = null;

    /**
     * Update date.
     *
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTimeInterface $updatedAt = null;

    /**
     * {@inheritDoc}
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * {@inheritDoc}
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * {@inheritDoc}
     */
    public function setCreatedAt(?DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}