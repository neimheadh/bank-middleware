<?php

namespace App\Model\Entity;

use DateTimeInterface;

/**
 * Entity with traced dates.
 */
interface DatedEntityInterface
{

    /**
     * Get entity creation date.
     *
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface;

    /**
     * Get entity update date.
     *
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface;

    /**
     * Set entity creation date.
     *
     * @param DateTimeInterface $date Creation date.
     *
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $date): self;

    /**
     * Set entity update date.
     *
     * @param DateTimeInterface $date Update date.
     *
     * @return $this
     */
    public function setUpdatedAt(DateTimeInterface $date): self;
}