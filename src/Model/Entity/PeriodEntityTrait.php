<?php

namespace App\Model\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity with start & end date trait.
 */
trait PeriodEntityTrait
{

    /**
     * End date.
     *
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'date', nullable: true)]
    private ?DateTimeInterface $endAt = null;

    /**
     * Start date.
     *
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'date', nullable: true)]
    private ?DateTimeInterface $startAt = null;

    /**
     * {@inheritDoc}
     */
    public function getEndAt(): ?DateTimeInterface
    {
        return $this->endAt;
    }

    /**
     * {@inheritDoc}
     */
    public function getStartAt(): ?DateTimeInterface
    {
        return $this->startAt;
    }

    /**
     * {@inheritDoc}
     */
    public function setEndAt(?DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setStartAt(?DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;
        return $this;
    }

}