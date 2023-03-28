<?php

namespace App\Model\Entity\Generic;

use DateTimeInterface;

/**
 * Entity with created and updated date.
 */
interface DatedEntityInterface
{

    /**
     * Get creation date.
     *
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface;

    /**
     * Get update date.
     *
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface;

    /**
     * Set creation date.
     *
     * @param DateTimeInterface $date Creation date.
     *
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $date): self;

    /**
     * Set update date.
     *
     * @param DateTimeInterface $date Update date.
     *
     * @return $this
     */
    public function setUpdatedAt(DateTimeInterface $date): self;
}