<?php

namespace App\Model\Entity;

use DateTimeInterface;

/**
 * Refreshed entity.
 */
interface RefreshedEntityInterface extends PeriodicEntityInterface,
                                           PeriodEntityInterface
{

    /**
     * Get last refresh date.
     *
     * @return DateTimeInterface
     */
    public function getRefreshedAt(): DateTimeInterface;

    /**
     * Get if the refresh needed.
     *
     * @return bool
     */
    public function needRefresh(): bool;

    /**
     * Refresh entity.
     *
     * @return void
     */
    public function refresh(): void;

    /**
     * Set last refresh date.
     *
     * @param DateTimeInterface $date Last refresh date.
     *
     * @return $this
     */
    public function setRefreshedAt(DateTimeInterface $date): self;

}