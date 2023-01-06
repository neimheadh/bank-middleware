<?php

namespace App\Model\Entity;

use DateTimeInterface;

/**
 * Entity with start & end date.
 */
interface PeriodEntityInterface
{

    /**
     * Get end date.
     *
     * @return DateTimeInterface|null
     */
    public function getEndAt(): ?DateTimeInterface;

    /**
     * Get start date.
     *
     * @return DateTimeInterface|null
     */
    public function getStartAt(): ?DateTimeInterface;

    /**
     * Set end date.
     *
     * @param DateTimeInterface $date End date.
     *
     * @return $this
     */
    public function setEndAt(DateTimeInterface $date): self;

    /**
     * Set start date.
     *
     * @param DateTimeInterface $date Start date.
     *
     * @return $this
     */
    public function setStartAt(DateTimeInterface $date): self;
}