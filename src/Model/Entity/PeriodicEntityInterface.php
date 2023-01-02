<?php

namespace App\Model\Entity;

/**
 * Entity with periodicity.
 */
interface PeriodicEntityInterface
{

    /**
     * Get periodicity.
     *
     * @return string|null
     */
    public function getPeriodicity(): ?string;

    /**
     * Set periodicity.
     *
     * @param string|null $periodicity Entity periodicity.
     *
     * @return $this
     */
    public function setPeriodicity(?string $periodicity): self;

}