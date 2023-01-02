<?php

namespace App\Model\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity with traced date trait.
 */
trait DatedEntityTrait
{

    /**
     * Creation date.
     *
     * @var DateTimeInterface|null
     */
    #[ORM\Column(
      type: 'datetime',
      nullable: false,
      options: ['default' => 'CURRENT_TIMESTAMP']
    )]
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
    public function setCreatedAt(?DateTimeInterface $date): self
    {
        $this->createdAt = $date;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setUpdatedAt(?DateTimeInterface $date): self
    {
        $this->updatedAt = $date;
        return $this;
    }
}